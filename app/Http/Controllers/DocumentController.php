<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
	public function index()
	{
		// Obtener la cantidad de documentos por relevancia
		$documentosPorRelevancia = Document::select('relevancia', \DB::raw('count(*) as total'))
			->groupBy('relevancia')
			->pluck('total', 'relevancia')
			->toArray();

		// Obtener la cantidad de documentos aprobados por mes del último año
		$documentosAprobadosPorMes = Document::select(
			\DB::raw("DATE_FORMAT(fecha_aprobacion, '%Y-%m') as mes"),
			\DB::raw('count(*) as total')
		)
		->where('aprobado', true)
		->whereYear('fecha_aprobacion', now()->year)
		->groupBy('mes')
		->orderBy('mes', 'asc')
		->pluck('total', 'mes')
		->toArray();

		// Obtener la lista completa de documentos para el listado
		$documents = Document::with(['aprobadoPor', 'revisadoPor'])->get();

		return view('documents.index', compact('documentosPorRelevancia', 'documentosAprobadosPorMes', 'documents'));
	}

    // Método para subir y guardar un nuevo documento
	public function store(Request $request)
	{
		if (!auth()->user()->can('crear documentos')) {
			abort(403, 'No tienes permisos para realizar esta acción.');
		}
		// Validar los datos recibidos
		$request->validate([
			'title' => 'required|string|max:255',
			'description' => 'required|string',
			'relevancia' => 'required|in:Alta,Media,Baja',
			'fecha_aprobacion' => 'required|date',
			'file' => 'required|mimes:pdf|max:2048' // Solo se permiten archivos PDF con tamaño máximo de 2MB
		]);

		// Verificar el tipo de archivo con `finfo`
		$file = $request->file('file');
		$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimeType = finfo_file($fileInfo, $file->getRealPath());
		finfo_close($fileInfo);

		// Asegurarse que el tipo MIME sea PDF
		if ($mimeType !== 'application/pdf') {
			return redirect()->back()->withErrors(['file' => 'El archivo no es un PDF válido.']);
		}

		// Analizar el archivo en busca de malware usando ClamAV (si está instalado en el servidor)
		$scanResult = shell_exec("clamscan " . escapeshellarg($file->getRealPath()));
		if (strpos($scanResult, 'OK') === false) {
			return redirect()->back()->withErrors(['file' => 'El archivo contiene malware y no se puede subir.']);
		}

		// Almacenar el archivo en la carpeta 'documents'
		$filePath = $file->store('documents');

		// Calcular y almacenar el hash del archivo para futuras verificaciones
		$fileHash = hash_file('sha256', $file->getRealPath());

		// Crear un nuevo registro en la base de datos con toda la información
		Document::create([
			'title' => $request->title,
			'description' => $request->description,
			'relevancia' => $request->relevancia,
			'fecha_aprobacion' => $request->fecha_aprobacion,
			'path' => $filePath,
			'user_id' => auth()->user()->id,
			'file_hash' => $fileHash // Guardar el hash del archivo en la base de datos
		]);

		return redirect()->back()->with('success', 'Documento subido exitosamente');
	}

	public function approve(Request $request, $id)
	{
		if (!auth()->user()->can('aprobar documentos')) {
			abort(403, 'No tienes permisos para aprobar documentos.');
		}

		$document = Document::findOrFail($id);

		// Marcar como aprobado y registrar el usuario que lo aprobó
		$document->update([
			'aprobado' => true,
			'aprobado_por' => auth()->user()->id,
			'revisado_por' => null // Borrar registro de rechazo si fue aprobado
		]);

		return redirect()->back()->with('success', 'Documento aprobado exitosamente');
	}

	public function reject(Request $request, $id)
	{
		if (!auth()->user()->can('rechazar documentos')) {
			abort(403, 'No tienes permisos para rechazar documentos.');
		}
		$document = Document::findOrFail($id);

		// Marcar como no aprobado y registrar el usuario que lo rechazó
		$document->update([
			'aprobado' => false,
			'revisado_por' => auth()->user()->id,
			'aprobado_por' => null // Borrar registro de aprobación si fue rechazado
		]);

		return redirect()->back()->with('success', 'Documento rechazado exitosamente');
	}

    // Método para mostrar un documento específico
    public function show($id)
    {
        // Buscar el documento por ID
        $document = Document::findOrFail($id);
        return view('documents.show', compact('document'));
    }

    // Método para eliminar un documento
    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Eliminar el archivo del almacenamiento
        Storage::delete($document->path);

        // Eliminar el registro de la base de datos
        $document->delete();

        return redirect()->back()->with('success', 'Documento eliminado exitosamente');
    }

	public function documentsByRelevance()
	{
		// Obtener los documentos agrupados por relevancia con su respectiva cantidad
		$documentosPorRelevancia = Document::select('relevancia', \DB::raw('count(*) as total'))
			->groupBy('relevancia')
			->pluck('total', 'relevancia');

		return response()->json($documentosPorRelevancia);
	}

}
<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller {
	public function index(Request $request) {
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
		$query = Document::with(['aprobadoPor', 'revisadoPor']);

		// Filtrar por relevancia si se ha seleccionado
		if ($request->filled('relevancia')) {
			$query->where('relevancia', $request->relevancia);
		}

		// Ordenar por fecha de subida
		if ($request->filled('order')) {
			$order = $request->order === 'desc' ? 'desc' : 'asc';
			$query->orderBy('created_at', $order);
		}

		// Búsqueda por nombre
		if ($request->filled('search')) {
			$query->where('title', 'like', '%' . $request->search . '%')
				  ->orWhere('description', 'like', '%' . $request->search . '%');
		}

		$documents = $query->get();

		return view('documents.index', compact('documentosPorRelevancia', 'documentosAprobadosPorMes', 'documents'));
	}

    // Método para subir y guardar un nuevo documento
	public function store(Request $request) {
		if (!auth()->user()->can('crear documentos')) {
			abort(403, 'No tienes permisos para realizar esta acción.');
		}

		// Validar los datos del formulario
		$request->validate([
			'title' => 'required|string|max:255',
			'description' => 'required|string',
			'relevancia' => 'required|in:Alta,Media,Baja',
			'file' => 'required|mimes:pdf|max:2048' // Solo archivos PDF con tamaño máximo de 2MB
		]);

		// Almacenar el archivo
		if ($request->hasFile('file')) {
			$filePath = $request->file('file')->store('documents');

			// Crear el registro del documento
			Document::create([
				'title' => $request->input('title'),
				'description' => $request->input('description'),
				'relevancia' => $request->input('relevancia'),
				'path' => $filePath,
				'user_id' => auth()->user()->id,
			]);

			return response()->json(['success' => 'Documento subido exitosamente']);
		}

		return response()->json(['error' => 'El archivo no pudo ser subido'], 400);
	}

    // Método para mostrar un documento específico
    public function show($id) {
        // Buscar el documento por ID
        $document = Document::findOrFail($id);
        return view('documents.show', compact('document'));
    }

	public function approve(Request $request, $id)
	{
		if (!auth()->user()->can('aprobar documentos')) {
			abort(403, 'No tienes permisos para aprobar documentos.');
		}

		$document = Document::findOrFail($id);

		// Actualizar el estado de aprobación
		$document->update([
			'aprobado' => true,
			'fecha_aprobacion' => now(),
			'aprobado_por' => auth()->user()->id,
			'revisado_por' => null
		]);

		return response()->json(['message' => 'Documento aprobado exitosamente.'], 200);
	}

	public function reject(Request $request, $id) {
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

		return response()->json(['message' => 'Documento rechazado exitosamente.'], 200);
	}

    // Método para eliminar un documento
	public function destroy($id)
	{
		if (!auth()->user()->can('borrar documentos')) {
			return response()->json(['error' => 'No tienes permisos para eliminar documentos.'], 403);
		}

		$document = Document::findOrFail($id);

		// Eliminar el archivo del almacenamiento
		Storage::delete($document->path);

		// Eliminar el registro de la base de datos
		$document->delete();

		return response()->json(['message' => 'Documento eliminado exitosamente.'], 200);
	}

	// Método para descargar el documento
	public function download($id) {
		// Buscar el documento por ID
		$document = Document::findOrFail($id);

		// Verificar si el archivo existe en el almacenamiento
		if (!Storage::exists($document->path)) {
			return response()->json(['error' => 'El archivo no se encuentra disponible.'], 404);
		}

		// Descargar el archivo
		return Storage::download($document->path, $document->title . '.pdf');
	}

	// Método Api ENDPOINT.

public function apiIndex(Request $request) {
    // Obtener filtros y órdenes
    $relevancia = $request->input('relevancia');
    $order = $request->input('order', 'created_at'); // Orden por fecha por defecto
    $direction = $request->input('direction', 'asc'); // Ascendente por defecto
    $search = $request->input('search'); // Filtro por título o descripción

    // Validar el parámetro order para que sea una columna válida
    $validOrders = ['created_at', 'updated_at', 'relevancia']; // Lista de columnas permitidas
    if (!in_array($order, $validOrders)) {
        $order = 'created_at'; // Revertir a created_at si el valor no es válido
    }

    // Construir consulta base
    $documents = Document::query();

    // Filtrar por relevancia si se ha proporcionado
    if ($relevancia) {
        $documents->where('relevancia', $relevancia);
    }

    // Filtrar por búsqueda en título o descripción
    if ($search) {
        $documents->where(function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Ordenar los documentos
    $documents->orderBy($order, $direction);

    // Obtener los documentos paginados
    $documents = $documents->with(['aprobadoPor', 'user'])->paginate(10);

    // Retornar respuesta en formato JSON
    return response()->json($documents);
}


	// Método para obtener las estadísticas en json
    public function getDocumentStatistics() {
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

        return response()->json([
            'documentosPorRelevancia' => $documentosPorRelevancia,
            'documentosAprobadosPorMes' => $documentosAprobadosPorMes,
        ]);
    }

}
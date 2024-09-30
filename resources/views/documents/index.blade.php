<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Documentos Corporativos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensaje de éxito -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Formulario de subida de documentos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                                <input type="text" name="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300" required>
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300" required></textarea>
                            </div>
                            <div>
                                <label for="relevancia" class="block text-sm font-medium text-gray-700">Relevancia</label>
                                <select name="relevancia" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300" required>
                                    <option value="">Seleccione la relevancia</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Media">Media</option>
                                    <option value="Baja">Baja</option>
                                </select>
                            </div>
                            <div>
                                <label for="fecha_aprobacion" class="block text-sm font-medium text-gray-700">Fecha de Aprobación</label>
                                <input type="date" name="fecha_aprobacion" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300" required>
                            </div>
                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700">Archivo PDF</label>
                                <input type="file" name="file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300" required>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">
                            Subir Documento
                        </button>
                    </form>
                </div>
            </div>

						<!-- Listado de Documentos -->
			<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
				<div class="p-6 bg-white border-b border-gray-200">
					<h3 class="text-lg font-semibold mb-4">Listado de Documentos</h3>
					<table class="min-w-full divide-y divide-gray-200">
						<thead class="bg-gray-50">
							<tr>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Relevancia</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Aprobación</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Subida</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
							</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200">
							@foreach ($documents as $document)
								<tr>
									<td class="px-6 py-4 whitespace-nowrap">{{ $document->title }}</td>
									<td class="px-6 py-4 whitespace-nowrap">{{ $document->description }}</td>
									<td class="px-6 py-4 whitespace-nowrap">{{ $document->relevancia }}</td>
									<td class="px-6 py-4 whitespace-nowrap">
										@if ($document->aprobado)
											{{ $document->fecha_aprobacion->format('d-m-Y') }} por {{ optional($document->aprobadoPor)->name ?? 'No disponible' }}
										@else
											No aprobado
										@endif
									</td>
									<td class="px-6 py-4 whitespace-nowrap">{{ $document->created_at->format('d-m-Y') }} por {{ optional($document->user)->name }}</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<a href="{{ Storage::url($document->path) }}" class="text-blue-600 hover:text-blue-900">Descargar</a>
										@if (auth()->user()->can('aprobar documentos') && !$document->aprobado)
											<form action="{{ route('documents.approve', $document->id) }}" method="POST" class="inline">
												@csrf
												@method('PATCH')
												<button type="submit" class="text-green-600 hover:text-green-900">Aprobar</button>
											</form>
										@endif
										@if (auth()->user()->can('rechazar documentos') && !$document->aprobado)
											<form action="{{ route('documents.reject', $document->id) }}" method="POST" class="inline">
												@csrf
												@method('PATCH')
												<button type="submit" class="text-red-600 hover:text-red-900">Rechazar</button>
											</form>
										@endif
										@if (auth()->user()->can('borrar documentos'))
											<form action="{{ route('documents.destroy', $document->id) }}" method="POST" class="inline">
												@csrf
												@method('DELETE')
												<button type="submit" class="text-yellow-600 hover:text-yellow-900">Eliminar</button>
											</form>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>

            <!-- Gráficos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                <h3 class="font-semibold text-lg mb-4">Estadísticas de Documentos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Gráfico Circular -->
                    <div>
                        <h4 class="text-lg font-medium">Cantidad de Documentos por Relevancia</h4>
                        <canvas id="relevanciaChart" width="400" height="200"></canvas>
                    </div>

                    <!-- Gráfico Lineal -->
                    <div>
                        <h4 class="text-lg font-medium">Cantidad de Documentos Aprobados por Mes (Último Año)</h4>
                        <canvas id="aprobadosChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para los gráficos -->
<!-- Scripts para los gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico Circular de Relevancia
    const relevanciaData = @json(array_values($documentosPorRelevancia));
    const relevanciaLabels = @json(array_keys($documentosPorRelevancia));

    const relevanciaCtx = document.getElementById('relevanciaChart').getContext('2d');
    new Chart(relevanciaCtx, {
        type: 'doughnut',
        data: {
            labels: relevanciaLabels,
            datasets: [{
                label: 'Cantidad de Documentos',
                data: relevanciaData,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
            }]
        }
    });

    // Gráfico Lineal de Documentos Aprobados por Mes
    const aprobadosData = @json(array_values($documentosAprobadosPorMes));
    const aprobadosLabels = @json(array_keys($documentosAprobadosPorMes));

    const aprobadosCtx = document.getElementById('aprobadosChart').getContext('2d');
    new Chart(aprobadosCtx, {
        type: 'line',
        data: {
            labels: aprobadosLabels,
            datasets: [{
                label: 'Documentos Aprobados',
                data: aprobadosData,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }]
        }
    });
</script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Documentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div id="message-container"></div>
            <!-- Formulario de subida de documentos | js en resources/js/upload-document.js -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 bg-white border-b border-gray-200">
					<h3 class="text-lg font-semibold mb-4">Subir nuevo documento</h3>
					<form action="{{ route('documents.store') }}" class="dropzone" id="document-dropzone" style="border: none !important; box-shadow: none !important; background-color: #f9fafb;">
						@csrf
						<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
							<div>
								<label for="title" class="block text-sm font-medium text-gray-700">Título</label>
								<input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300" required>
							</div>
							<div>
								<label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
								<textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300" required></textarea>
							</div>
							<div>
								<label for="relevancia" class="block text-sm font-medium text-gray-700">Relevancia</label>
								<select name="relevancia" id="relevancia" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300" required>
									<option value="">Seleccione la relevancia</option>
									<option value="Alta">Alta</option>
									<option value="Media">Media</option>
									<option value="Baja">Baja</option>
								</select>
							</div>
						</div>
						<div class="mt-4">
							<!-- Contenedor Dropzone para subir archivos -->
							<div class="dropzone bg-gray-50 border-2 border-dashed border-gray-300 p-4 rounded-md mt-4" id="file-dropzone" data-url="{{ route('documents.store') }}">
								<div class="dz-message" data-dz-message>
									<span>Arrastra y suelta tu archivo PDF aquí o haz clic para seleccionarlo</span>
								</div>
							</div>
						</div>
						<button type="button" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700" id="submit-document">
							Subir Documento
						</button>
					</form>
                </div>
            </div>

			<!-- Listado de Documentos -->
			<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
				<div class="p-6 bg-white border-b border-gray-200">
					<h3 class="text-lg font-semibold mb-4">Listado de Documentos</h3>
					<div class="mb-4 flex justify-between">
						<div class="flex space-x-4">
							<!-- Campo de búsqueda -->
							<input type="text" id="search" placeholder="Buscar por nombre" class="border rounded p-2" />
							<!-- Selector de relevancia con ajuste de estilos -->
							<select id="filter-relevancia" class="border rounded p-2 w-32 h-10 text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm sm:leading-5">
								<option value="">Relevancia</option>
								<option value="Alta">Alta</option>
								<option value="Media">Media</option>
								<option value="Baja">Baja</option>
							</select>

							<!-- Selector de orden con ajuste de tamaño y altura -->
							<select id="order-date" class="border rounded p-2 w-32 h-10 text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm sm:leading-5">
								<option value="">Fecha</option>
								<option value="desc">Descendente</option>
								<option value="asc">Ascendente</option>
							</select>
						</div>
					</div>
					<table class="min-w-full divide-y divide-gray-200">
						<thead class="bg-gray-50">
							<tr>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Relevancia</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Aprobación</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Subida</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
							</tr>
						</thead>
						<tbody id="document-table-body" class="bg-white divide-y divide-gray-200">
							<!-- Aquí se agregarán los documentos mediante JavaScript resources/js/documents.js -->
						</tbody>
					</table>	
				</div>
			</div>
			<!-- Gráficos -->
			<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
				<!-- Columna 1: Gráfico Circular de Relevancia -->
				<div class="md:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
					<h3 class="font-semibold text-lg mb-4">Documentos por relevancia</h3>
					<div id="relevanciaChartContainer">
						<canvas id="relevanciaChart" width="100" height="100"></canvas>
					</div>
				</div>

				<!-- Columna 2: Gráfico Lineal de Documentos por Año -->
				<div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
					<h3 class="font-semibold text-lg mb-4">Documentos {{ date('Y') }}</h3>
					<div id="aprobadosChartContainer">
						<canvas id="aprobadosChart" width="400" height="200"></canvas>
					</div>
				</div>
			</div>
        </div>
    </div>
	<!-- Script para los gráficos -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<!-- Script endpoint -->
	@vite('resources/js/documents.js')
	@vite('resources/js/upload-document.js')
</x-app-layout>
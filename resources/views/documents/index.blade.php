<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos Corporativos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Documentos Corporativos</h1>
        
        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario de subida de documentos -->
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="relevancia" class="form-label">Relevancia</label>
                <select name="relevancia" class="form-select" required>
                    <option value="">Seleccione la relevancia</option>
                    <option value="Alta">Alta</option>
                    <option value="Media">Media</option>
                    <option value="Baja">Baja</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_aprobacion" class="form-label">Fecha de Aprobación</label>
                <input type="date" name="fecha_aprobacion" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Archivo PDF</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Subir Documento</button>
        </form>

        <!-- Listado de Documentos -->
        <h2>Listado de Documentos</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Relevancia</th>
                    <th>Fecha de Aprobación</th>
                    <th>Fecha de Subida</th>
                    <th>Aprobado</th>
                    <th>Aprobado por</th>
                    <th>Revisado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $document)
                    <tr>
                        <td>{{ $document->title }}</td>
                        <td>{{ $document->description }}</td>
                        <td>{{ $document->relevancia }}</td>
                        <td>{{ $document->fecha_aprobacion }}</td>
                        <td>{{ $document->created_at->format('d-m-Y') }}</td>
                        <td>{{ $document->aprobado ? 'Sí' : 'No' }}</td>
                        <td>{{ $document->aprobadoPor ? $document->aprobadoPor->name : '' }}</td>
                        <td>{{ $document->revisadoPor ? $document->revisadoPor->name : '' }}</td>
                        <td>
                            @if (auth()->user()->can('aprobar documentos') && !$document->aprobado)
                                <form action="{{ route('documents.approve', $document->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
                                </form>
                            @endif

                            @if (auth()->user()->can('rechazar documentos') && !$document->aprobado)
                                <form action="{{ route('documents.reject', $document->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                                </form>
                            @endif

                            @if (auth()->user()->can('borrar documentos'))
                                <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-warning btn-sm">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Gráficos -->
        <div class="row mt-5">
            <!-- Gráfico Circular -->
            <div class="col-md-6">
                <h3>Cantidad de Documentos por Relevancia</h3>
                <canvas id="relevanciaChart" width="400" height="200"></canvas>
            </div>

            <!-- Gráfico Lineal -->
            <div class="col-md-6">
                <h3>Cantidad de Documentos Aprobados por Mes (Último Año)</h3>
                <canvas id="aprobadosChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

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
</body>
</html>

import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import Swal from 'sweetalert2';

class DocumentAPI {
    constructor(baseURL, token) {
        this.baseURL = baseURL;
        this.token = token;
    }

    // Método para obtener los documentos desde la API
    async getDocuments(relevancia = '', order = 'created_at', direction = 'desc', search = '', page = 1) {
        try {
            const response = await fetch(`${this.baseURL}/api/documents?relevancia=${relevancia}&order=${order}&direction=${direction}&search=${search}&page=${page}`, {
                headers: {
                    'Authorization': `Bearer ${this.token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`Error: ${response.status} ${response.statusText}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching documents:', error);
            return null;
        }
    }

    // Método para obtener las estadísticas de documentos
    async getDocumentStatistics() {
        try {
            const response = await fetch(`${this.baseURL}/api/document-statistics`, {
                headers: {
                    'Authorization': `Bearer ${this.token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`Error: ${response.status} ${response.statusText}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching document statistics:', error);
            return null;
        }
    }

    // Método para aprobar un documento
	async approveDocument(documentId) {
		try {
			const response = await fetch(`/documents/approve/${documentId}`, {
				method: 'PATCH', // Usar método PATCH
				headers: {
					'Authorization': `Bearer ${this.token}`,
					'Accept': 'application/json',
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
				}
			});

			// Validar si el status de la respuesta es 200 (éxito)
			if (response.status === 200) {
				const data = await response.json();
				Swal.fire('Aprobado', data.message, 'success');
				this.getDocuments().then(data => this.renderDocuments(data, ['aprobar documentos', 'rechazar documentos', 'borrar documentos']));
				return data;
			} else {
				const errorData = await response.json();
				throw new Error(errorData.message || 'Ocurrió un error al aprobar el documento.');
			}
		} catch (error) {
			console.error('Error approving document:', error);
			Swal.fire('Error', error.message, 'error');
		}
	}

	async rejectDocument(documentId) {
		try {
			const response = await fetch(`/documents/reject/${documentId}`, {
				method: 'PATCH',
				headers: {
					'Authorization': `Bearer ${this.token}`,
					'Accept': 'application/json',
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
				}
			});

			// Validar si el status de la respuesta es 200 (éxito)
			if (response.status === 200) {
				const data = await response.json();
				Swal.fire('Rechazado', data.message, 'success');
				this.getDocuments().then(data => this.renderDocuments(data, ['aprobar documentos', 'rechazar documentos', 'borrar documentos']));
				return data;
			} else {
				const errorData = await response.json();
				throw new Error(errorData.message || 'Ocurrió un error al rechazar el documento.');
			}
		} catch (error) {
			console.error('Error rejecting document:', error);
			Swal.fire('Error', error.message, 'error');
		}
	}

    // Método para eliminar un documento
	async deleteDocument(documentId) {
		try {
			const response = await fetch(`${this.baseURL}/documents/${documentId}`, {
				method: 'DELETE',
					headers: {
						'Authorization': `Bearer ${this.token}`,
						'Accept': 'application/json',
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
					}
			});

			// Validar si el status de la respuesta es 200 (éxito)
			if (response.status === 200) {
				const data = await response.json();
				Swal.fire('Eliminado', data.message, 'success');
				this.getDocuments().then(data => this.renderDocuments(data, ['aprobar documentos', 'rechazar documentos', 'borrar documentos']));
				return data;
			} else {
				const errorData = await response.json();
				throw new Error(errorData.message || 'Ocurrió un error al eliminar el documento.');
			}
		} catch (error) {
			console.error('Error deleting document:', error);
			Swal.fire('Error', error.message, 'error');
		}
	}

    // Método para descargar documentos a través de la API
    async downloadDocument(documentId, documentTitle) {
        try {
            const response = await fetch(`${this.baseURL}/documents/download/${documentId}`, {
                headers: {
                    'Authorization': `Bearer ${this.token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });

            if (!response.ok) {
                // Si el archivo no se encuentra disponible, mostrar error
                if (response.status === 404) {
                    alert(`El documento "${documentTitle}" no está disponible para su descarga.`);
                }
                throw new Error(`Error: ${response.status} ${response.statusText}`);
            }

            // Crear un blob con el archivo descargado
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);

            // Crear un enlace temporal para descargar el archivo
            const a = document.createElement('a');
            a.href = url;
            a.download = `${documentTitle}.pdf`; // Nombre del archivo
            document.body.appendChild(a);
            a.click();

            // Eliminar el enlace temporal
            a.remove();
            window.URL.revokeObjectURL(url);

        } catch (error) {
            console.error('Error downloading document:', error);
        }
    }

    // Método para renderizar los gráficos de estadísticas de documentos
    renderDocumentCharts(statistics) {
        // Gráfico Circular de Relevancia
        const relevanciaData = Object.values(statistics.documentosPorRelevancia);
        const relevanciaLabels = Object.keys(statistics.documentosPorRelevancia);
        const relevanciaCtx = document.getElementById('relevanciaChart').getContext('2d');

        if (relevanciaData.length > 0) {
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
        } else {
            document.getElementById('relevanciaChartContainer').innerHTML = '<p>No hay datos de documentos para mostrar.</p>';
        }

        // Crear la lista completa de meses del año
        const fullMonths = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        // Inicializar los datos de documentos aprobados con cero para todos los meses
        const documentosAprobadosPorMes = new Array(12).fill(0);

        // Asignar valores reales de documentos aprobados a los meses correspondientes
        Object.keys(statistics.documentosAprobadosPorMes).forEach(monthKey => {
            const monthIndex = parseInt(monthKey.split('-')[1], 10) - 1;
            documentosAprobadosPorMes[monthIndex] = statistics.documentosAprobadosPorMes[monthKey];
        });

        // Gráfico Lineal de Documentos Aprobados por Mes
        const aprobadosCtx = document.getElementById('aprobadosChart').getContext('2d');

        new Chart(aprobadosCtx, {
            type: 'line',
            data: {
                labels: fullMonths,
                datasets: [{
                    label: 'Documentos Aprobados',
                    data: documentosAprobadosPorMes,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                            callback: function(value) {
                                return Number.isInteger(value) ? value : null;
                            }
                        }
                    }
                }
            }
        });
    }

    // Método para renderizar los documentos en la tabla
	renderDocuments(documents, userPermissions) {
		const tableBody = document.getElementById('document-table-body');
		tableBody.innerHTML = ''; // Limpiar la tabla

		documents.data.forEach(document => {
			// Crear los botones con íconos
			let actions = `
				<div class="flex flex-col space-y-1">
					<button type="button" class="download-btn text-blue-600 hover:text-blue-900 flex items-center" data-id="${document.id}" data-title="${document.title}">
						<i class="fas fa-download mr-2"></i> Descargar
					</button>
			`;

			// Solo mostrar el botón de aprobar si el usuario tiene permiso y el documento no está aprobado
			if (userPermissions.includes('aprobar documentos') && !document.aprobado) {
				actions += `
					<button type="button" class="approve-btn text-green-600 hover:text-green-900 flex items-center" data-id="${document.id}">
						<i class="fas fa-check-circle mr-2"></i> Aprobar
					</button>
				`;
			}

			// Solo mostrar el botón de rechazar si el usuario tiene permiso y el documento no está aprobado
			if (userPermissions.includes('rechazar documentos') && !document.aprobado) {
				actions += `
					<button type="button" class="reject-btn text-red-600 hover:text-red-900 flex items-center" data-id="${document.id}">
						<i class="fas fa-times-circle mr-2"></i> Rechazar
					</button>
				`;
			}

			// Mostrar el botón de eliminar si el usuario tiene permiso
			if (userPermissions.includes('borrar documentos')) {
				actions += `
					<button type="button" class="delete-btn text-yellow-600 hover:text-yellow-900 flex items-center" data-id="${document.id}">
						<i class="fas fa-trash-alt mr-2"></i> Eliminar
					</button>
				`;
			}

			actions += `</div>`;

			// Crear la fila con los datos del documento y las acciones
			const row = `
				<tr>
					<td class="px-6 py-4 whitespace-nowrap">${document.title}</td>
					<td class="px-6 py-4 whitespace-nowrap">
						<span class="cursor-pointer" data-tippy-content="${document.description}">
							<i class="fas fa-comment-alt text-gray-500 mr-1"></i> Descripción
						</span>
					</td>
					<td class="px-6 py-4 whitespace-nowrap">${document.relevancia}</td>
					<td class="px-6 py-4 whitespace-nowrap">
						${document.aprobado ? (document.fecha_aprobacion ? new Date(document.fecha_aprobacion).toLocaleDateString() : 'No disponible') : 'No aprobado'}
					</td>
					<td class="px-6 py-4 whitespace-nowrap">${new Date(document.created_at).toLocaleDateString()} por ${document.user ? document.user.name : 'No disponible'}</td>
					<td class="px-6 py-4 whitespace-nowrap">${actions}</td>
				</tr>
			`;
			tableBody.innerHTML += row;
		});

		// Inicializar Tippy.js para los elementos con el atributo 'data-tippy-content'
		tippy('[data-tippy-content]', {
			theme: 'light',
			animation: 'scale',
			delay: [200, 100], // Mostrar y ocultar con un pequeño retraso
		});

		this.assignActionEvents(); // Asignar eventos de botones
	}

    // Método para asignar los eventos a los botones de la tabla
    assignActionEvents() {
        document.querySelectorAll('.download-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const documentId = event.target.dataset.id;
                const documentTitle = event.target.dataset.title;
                this.downloadDocument(documentId, documentTitle);
            });
        });

        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', (event) => this.approveDocument(event.target.dataset.id));
        });

        document.querySelectorAll('.reject-btn').forEach(button => {
            button.addEventListener('click', (event) => this.rejectDocument(event.target.dataset.id));
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', (event) => this.deleteDocument(event.target.dataset.id));
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const userPermissions = ['aprobar documentos', 'rechazar documentos', 'borrar documentos']; // Permisos simulados, cámbialos según sea necesario.
    const documentAPI = new DocumentAPI('http://localhost:8000', 'TU_TOKEN');

    // Obtener y renderizar los documentos al cargar la página
    documentAPI.getDocuments().then(data => {
        if (data) {
            documentAPI.renderDocuments(data, userPermissions);
        }
    });

    // Obtener y renderizar los gráficos al cargar la página
    documentAPI.getDocumentStatistics().then(statistics => {
        if (statistics) {
            documentAPI.renderDocumentCharts(statistics);
        }
    });
});
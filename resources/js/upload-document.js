import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css';
import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function() {
    Dropzone.autoDiscover = false;

    const documentDropzone = new Dropzone("#file-dropzone", {
        url: document.getElementById('file-dropzone').getAttribute('data-url'),
        paramName: "file", // Nombre del campo de archivo que Dropzone enviará
        maxFilesize: 2, // Tamaño máximo en MB
        acceptedFiles: ".pdf", // Solo aceptar archivos PDF
        addRemoveLinks: true, // Mostrar opción de eliminar archivos
        autoProcessQueue: false, // Deshabilitar la subida automática
        parallelUploads: 5, // Número máximo de archivos a procesar en paralelo
        dictDefaultMessage: "Arrastra y suelta tu archivo PDF aquí o haz clic para seleccionarlo",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        init: function() {
            const submitButton = document.getElementById("submit-document");
            const dropzoneInstance = this;

            submitButton.addEventListener("click", function(e) {
                e.preventDefault();
                if (dropzoneInstance.getQueuedFiles().length > 0) {
                    dropzoneInstance.processQueue();
                } else {
                    Swal.fire('¡Atención!', 'Por favor, selecciona un archivo antes de subir.', 'warning');
                }
            });

            this.on("sending", function(file, xhr, formData) {
                formData.append("title", document.getElementById('title').value);
                formData.append("description", document.getElementById('description').value);
                formData.append("relevancia", document.getElementById('relevancia').value);
            });

            this.on("success", function(file, response) {
                Swal.fire('Éxito', 'El archivo se ha subido exitosamente.', 'success').then(() => {
                    location.reload(); // Refrescar la página después del éxito
                });
            });

            this.on("error", function(file, response) {
                Swal.fire('Error', `Hubo un problema al subir el archivo: ${response}`, 'error');
            });

            this.on("complete", function(file) {
                dropzoneInstance.removeFile(file);
            });
        }
    });
});

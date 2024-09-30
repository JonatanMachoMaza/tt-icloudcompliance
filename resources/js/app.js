import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Mostrar mensajes dinámicos
function showMessage(type, message) {
	const messageContainer = document.getElementById("message-container");
	messageContainer.innerHTML = `
		<div class="p-4 mb-4 text-sm text-${type === 'success' ? 'green' : 'red'}-700 bg-${type === 'success' ? 'green' : 'red'}-100 rounded-lg" role="alert">
			${message}
		</div>
	`;

	// Desaparecer el mensaje después de 3 segundos
	setTimeout(() => {
		messageContainer.innerHTML = "";
	}, 3000);
}

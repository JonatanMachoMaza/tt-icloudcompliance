<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 bg-white border-b border-gray-200">
					<h1 class="text-lg font-semibold">Bienvenido al sistema de gestión documental</h1>
					<p>Esta aplicación fue desarrollada como parte de una prueba técnica para icloudcompliance. El objetivo es gestionar documentos de manera eficiente, permitiendo a los usuarios subir, revisar y aprobar documentos según su relevancia y necesidades administrativas.</p>
<div>
    @if (Route::has('login'))
        <div class="flex justify-center space-x-4 mt-6">
            @auth
                <a href="{{ url('/dashboard') }}" class="bg-gray-800 text-white py-2 px-4 rounded-lg shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="bg-gray-800 text-white py-2 px-4 rounded-lg shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i> Iniciar sesión
                </a>
            @endauth
        </div>
    @endif
</div>

				</div>
            </div>
        </div>
    </div>
</x-app-layout>


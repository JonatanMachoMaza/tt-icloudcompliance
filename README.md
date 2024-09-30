# Prueba Técnica para iCloudCompliance

Este proyecto es una prueba técnica para iCloudCompliance. La aplicación está desarrollada usando **Laravel 11.25.0** y utiliza varias librerías para facilitar su funcionamiento:

- **Spatie/laravel-permission**: Para la gestión de roles y permisos.
- **Smaloat/pdfparser**: Para la manipulación de archivos PDF.

## Requisitos

- Base de datos: MySQL o MariaDB.

## Instalación

1. Clona este repositorio:
<pre>git clone https://github.com/JonatanMachoMaza/tt-icloudcompliance</pre>
2. Ejecuta las dependencias:
<pre>composer install</pre>
<pre>npm install</pre>
3. Configura tu archivo `.env` y ejecuta las migraciones con artisan:
<pre>php artisan key:generate</pre>
4. Ejecutra las migraciones y carga los datos iniciales:
<pre>php artisan migrate</pre>
<pre>php artisan db:seed</pre>

### Usuarios por defecto

Se crea un usuario por defecto con las siguientes credenciales:

- **Nombre:** Nombre del usuario
- **Email:** email@example.com
- **Contraseña:** test

## Uso

Accede a la aplicación a través de `http://localhost:8000`. Crea un usuario con permisos de administrador para gestionar los documentos.


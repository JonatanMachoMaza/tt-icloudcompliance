# Prueba Técnica para iCloudCompliance

Este proyecto es una prueba técnica para iCloudCompliance. La aplicación está desarrollada usando **Laravel 11.25.0** y utiliza varias librerías para facilitar su funcionamiento:

- **Spatie/laravel-permission**: Para la gestión de roles y permisos.
- **Smaloat/pdfparser**: Para la manipulación de archivos PDF.

## Requisitos

- Base de datos: MySQL o MariaDB.
- PHP 8.2
- Laravel
- Composer
- npm

## Instalación

1. Clona este repositorio:
<pre>git clone https://github.com/JonatanMachoMaza/tt-icloudcompliance</pre>
2. Ejecuta las dependencias dentro del directorio donde instalaste el repositorio:
<pre>composer install</pre>
<pre>npm install</pre>
3. Configura tu DB con el archivo `.env` y ejecuta las migraciones con artisan:
<pre>
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=icloudcompliance
DB_USERNAME=TU_USUARIO
DB_PASSWORD=TU_CONTRASEÑA
</pre>
<pre>php artisan key:generate</pre>
4. Ejecuta las migraciones y carga los datos iniciales:
<pre>php artisan migrate</pre>
<pre>php artisan db:seed</pre>

### Usuarios por defecto

Se crea un usuario por defecto con las siguientes credenciales:

- **Nombre:** Admin
- **Email:** test@example.com
- **Contraseña:** password

## Uso

Accede a la aplicación a través de `http://localhost:8000`.


# Prueba Técnica para iCloudCompliance

Este proyecto es una prueba técnica para iCloudCompliance. La aplicación está desarrollada usando **Laravel 11.25.0** y utiliza varias librerías para facilitar su funcionamiento:

- **Spatie/laravel-permission**: Para la gestión de roles y permisos.
- **Smaloat/pdfparser**: Para la manipulación de archivos PDF.

## Requisitos

- Base de datos: MySQL o MariaDB.

## Instalación

1. Clona este repositorio.
2. Ejecuta `composer install` para instalar las dependencias.
3. Configura tu archivo `.env` y ejecuta las migraciones con `php artisan migrate`.
4. Carga los datos iniciales con `php artisan db:seed`.

### Usuarios por defecto

Se crea un usuario por defecto con las siguientes credenciales:

- **Nombre:** Nombre del usuario
- **Email:** email@example.com
- **Contraseña:** test

## Uso

Accede a la aplicación a través de `http://localhost:8000`. Crea un usuario con permisos de administrador para gestionar los documentos.


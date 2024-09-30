# Prueba Técnica para iCloudCompliance

Este proyecto es una prueba técnica para iCloudCompliance. La aplicación está desarrollada usando **Laravel 11.25.0** y se ha testeado y desarrollado con XAMPP: https://www.apachefriends.org/es/index.html.

## Dependencias del Proyecto

A continuación se detalla el uso de las principales dependencias implementadas en este proyecto:

- **[@fortawesome/fontawesome-free](https://www.npmjs.com/package/@fortawesome/fontawesome-free)**: Utilizada para decorar la aplicación con una amplia gama de iconos, haciendo la interfaz más intuitiva y visualmente atractiva. Los iconos se utilizan en botones, menús, formularios y otros elementos interactivos.

- **[chart.js](https://www.chartjs.org/)**: Implementada para crear gráficos interactivos y personalizables que permiten visualizar datos estadísticos, análisis y reportes de una forma gráfica. Soporta gráficos de barras, líneas, áreas y más, facilitando la interpretación de los datos.

- **[dropzone](https://www.dropzonejs.com/)**: Utilizada para la subida de archivos PDF, enfocándose en facilitar la carga de documentación a través de un área de arrastrar y soltar (drag & drop) que mejora la experiencia del usuario al momento de cargar múltiples archivos.

- **[sweetalert2](https://sweetalert2.github.io/)**: Empleada para mostrar alertas modales personalizadas que informan al usuario sobre el estado de sus acciones en la interfaz, ya sea con mensajes de éxito, advertencias, errores o información adicional. Esto proporciona una experiencia más atractiva y profesional comparado con las alertas tradicionales.

- **[tippy.js](https://atomiks.github.io/tippyjs/)**: Implementada para crear tooltips y popovers interactivos que muestran información adicional o descripciones al pasar el mouse sobre elementos específicos, sin ocupar espacio adicional en la interfaz. Facilita la comprensión de los contenidos sin recargar la vista principal.

- **[spatie/laravel-permission](https://spatie.be/docs/laravel-permission)**: Utilizada para la gestión de roles y permisos dentro de la aplicación Laravel. Permite asignar diferentes niveles de acceso a los usuarios, facilitando el control de funcionalidades y recursos a los que cada rol puede acceder.

- **[smalot/pdfparser](https://github.com/smalot/pdfparser)**: Usada para la manipulación y extracción de contenido de archivos PDF. Permite leer, procesar y extraer texto de los documentos, facilitando la gestión automatizada de la información contenida en archivos PDF.

## Requisitos

- Base de datos: MySQL o MariaDB.
- PHP 8.2: Extensiones OpenSSL y ZIP habilitados.
- Node.js
- Laravel
- Composer
- npm
- git

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


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
Usar terminal del SO en local.

En entorno de pruebas:
<pre>php artisan serve</pre>

En producción modifique su .env:
<pre>
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com (o http://localhost si es local)
</pre>

Apertura en producción desde local:
<pre>php artisan serve --env=production</pre>

Accede a la aplicación a en local `http://localhost:8000`.

## Documentación de la API

### Descripción
Esta API está diseñada para gestionar documentos dentro de la plataforma, con endpoints protegidos por middleware `auth`, lo cual significa que solo los usuarios autenticados pueden acceder a ellos. A continuación, se describen los endpoints disponibles y su funcionalidad:

### Endpoints

1. **GET /api/documents**
   - **Descripción**: Obtiene la lista de todos los documentos disponibles para el usuario autenticado.
   - **Protección**: Este endpoint está protegido por autenticación (`auth`), por lo que solo los usuarios autenticados pueden acceder a él.
   - **Respuesta**:
     ```json
     [
       {
         "id": 1,
         "titulo": "Documento 1",
         "estado": "aprobado",
         "fecha_creacion": "2024-09-30",
         ...
       },
       ...
     ]
     ```

2. **GET /api/document-statistics**
   - **Descripción**: Proporciona estadísticas relacionadas con los documentos, como la cantidad de documentos aprobados, rechazados o pendientes de revisión.
   - **Protección**: Requiere autenticación (`auth`) para acceder.
   - **Respuesta**:
     ```json
     {
       "total": 150,
       "aprobados": 120,
       "rechazados": 10,
       "pendientes": 20
     }
     ```

3. **GET /documents/download/{id}**
   - **Descripción**: Descarga un documento específico identificado por su ID.
   - **Protección**: Este endpoint está protegido por autenticación (`auth`). Solo los usuarios autenticados pueden descargar documentos.
   - **Parámetro**: 
     - `id` (int): ID del documento que se desea descargar.
   - **Respuesta**: Devuelve el archivo del documento para su descarga.

4. **POST /documents**
   - **Descripción**: Crea un nuevo documento en la plataforma.
   - **Protección**: Este endpoint especifica autenticación.
   - **Body**: El cuerpo de la solicitud debe contener la información necesaria para crear un documento.
     ```json
     {
       "titulo": "Nuevo Documento",
       "contenido": "Contenido del documento...",
       "autor_id": 5
     }
     ```
   - **Respuesta**: 
     ```json
     {
       "mensaje": "Documento creado exitosamente",
       "documento": {
         "id": 151,
         "titulo": "Nuevo Documento",
         "estado": "pendiente",
         ...
       }
     }
     ```

5. **PATCH /documents/approve/{id}**
   - **Descripción**: Aprueba un documento específico identificado por su ID.
   - **Protección**: Requiere autenticación y se asume que el usuario autenticado debe tener permisos para aprobar documentos.
   - **Parámetro**: 
     - `id` (int): ID del documento a aprobar.
   - **Respuesta**:
     ```json
     {
       "mensaje": "Documento aprobado exitosamente",
       "documento": {
         "id": 151,
         "estado": "aprobado",
         ...
       }
     }
     ```

6. **PATCH /documents/reject/{id}**
   - **Descripción**: Rechaza un documento específico identificado por su ID.
   - **Protección**: Requiere autenticación y se asume que el usuario autenticado debe tener permisos para rechazar documentos.
   - **Parámetro**: 
     - `id` (int): ID del documento a rechazar.
   - **Respuesta**:
     ```json
     {
       "mensaje": "Documento rechazado exitosamente",
       "documento": {
         "id": 151,
         "estado": "rechazado",
         ...
       }
     }
     ```

7. **DELETE /documents/{id}**
   - **Descripción**: Elimina un documento específico identificado por su ID.
   - **Protección**: Endpoint protegido por autenticación (`auth`). Solo los usuarios autenticados pueden acceder y deben tener permisos para eliminar documentos.
   - **Parámetro**: 
     - `id` (int): ID del documento a eliminar.
   - **Respuesta**:
     ```json
     {
       "mensaje": "Documento eliminado exitosamente"
     }
     ```



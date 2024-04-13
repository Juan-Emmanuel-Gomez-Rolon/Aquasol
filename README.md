# AQUASOL

## Instalación en Windows

### Requisitos

-   PHP (Viene con XAMPP) https://www.apachefriends.org/es/download.html
-   MySQL (Viene con XAMPP) https://www.apachefriends.org/es/download.html
-   Composer https://getcomposer.org/download/
-   Node.js (Se requiere para hacer build de los assets) https://nodejs.org/en/download

### Pasos

1. Clonar el repositorio
1. Iniciar XAMPP y asegurarse de que Apache y MySQL estén corriendo
1. Ir a la carpeta del proyecto y ejecutar `composer install`
1. Crear un archivo `.env` en la raíz del proyecto y copiar el contenido de `.env.example` en él
1. Configurar las credenciales de la base de datos en el archivo `.env`
1. Ejecutar `php artisan key:generate`
1. Ejecutar `php artisan migrate`, si la base de datos no existe, preguntará si desea crearla, escribir `yes`
1. Ejecutar `php artisan db:seed`, esto creará un usuario administrador con las credenciales `test@example.com` y `password`
1. Ejecutar `npm install` para instalar las dependencias de Node.js
1. Ejecutar `npm run build` para compilar los assets
1. Ejecutar `php artisan serve`, esto iniciará un servidor en `http://localhost:8000` y se podrá acceder a la aplicación desde el navegador.

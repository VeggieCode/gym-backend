Fase 1: El Backend (Laravel)
Vamos a crear el proyecto y los directorios de nuestras capas.


1. Crea el proyecto:

Bash
composer create-project laravel/laravel gym-backend
cd gym-backend
2. Crea la estructura Clean Architecture:
   Por defecto, Laravel trae sus carpetas (app/Models, app/Http). Vamos a crear nuestras propias carpetas dentro de app/ para que convivan pacíficamente con el framework.


Ejecuta esto en tu terminal:

Bash
mkdir -p app/Domain/Entities app/Domain/Repositories app/Domain/Exceptions
mkdir -p app/Application/UseCases app/Application/Contracts
mkdir -p app/Infrastructure/Repositories app/Infrastructure/Mappers app/Infrastructure/Payments
Nota: Los Controladores déjalos donde Laravel los pone por defecto (app/Http/Controllers), actuando como tu capa de Presentación.

mkdir -p app/Domain/Entities app/Domain/Repositories app/Domain/Exceptions
mkdir -p app/Application/UseCases app/Application/Contracts
mkdir -p app/Infrastructure/Repositories app/Infrastructure/Mappers app/Infrastructure/Payments

4. Un pequeño "Truco" (Alias)
   Para no tener que escribir ./vendor/bin/sail cada vez que quieras ejecutar un comando de Artisan (como crear migraciones o controladores), te recomiendo configurar este alias en tu terminal:

Bash
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'



Instalación de Laravel Sail

Laravel Sail (que funciona sobre Docker) es la mejor manera de trabajar hoy en día. Te da un entorno con PHP, MySQL y todo lo necesario sin tener que instalar "basura" directamente en tu sistema operativo.

Además, el script oficial de instalación de Laravel siempre descarga la versión más reciente y estable (Laravel 12 en este caso) por defecto.

Aquí tienes el paso a paso exacto para levantar tu gym-backend usando Sail.

1. Requisito Previo
   Asegúrate de tener Docker Desktop instalado y abierto en tu computadora (debe estar corriendo en segundo plano).

2. Ejecutar el script de instalación
   Abre tu terminal (si estás en Windows, te recomiendo usar WSL2 con Ubuntu, o la terminal de Git Bash/PowerShell) y ejecuta el siguiente comando.

Este script descargará la imagen de Docker, configurará Laravel 12 y preparará tu base de datos MySQL automáticamente:

Bash
curl -s "https://laravel.build/gym-backend" | bash
(Nota: Este proceso puede tardar unos minutos la primera vez, ya que tiene que descargar los contenedores de Docker de internet).

3. Levantar los contenedores
   Una vez que termine la instalación, el script te pedirá tu contraseña de administrador (para configurar los permisos) y te indicará que entres a la carpeta.

Entra al directorio de tu nuevo proyecto y levanta Sail en "modo desprendido" (background) con -d:

Bash
cd gym-backend
./vendor/bin/sail up -d
¡Y listo! Ya tienes Laravel 12 corriendo.

Tu aplicación está viva en: http://localhost

Tienes una base de datos MySQL corriendo en el puerto 3306.

4. Un pequeño "Truco" (Alias)
   Para no tener que escribir ./vendor/bin/sail cada vez que quieras ejecutar un comando de Artisan (como crear migraciones o controladores), te recomiendo configurar este alias en tu terminal:

Bash
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
Así, en lugar de escribir php artisan make:controller, ahora escribirás sail artisan make:controller, y el comando se ejecutará dentro de tu contenedor de Docker.

Paso 1: Preparar Docker y WSL
Antes de tirar código, Docker y Ubuntu deben estar hablando el mismo idioma.

Abre Docker Desktop en Windows.

Ve a la tuerca de configuración (Settings) > Resources > WSL Integration.

Asegúrate de que la opción "Enable integration with my default WSL distro" esté activa y que el "switch" al lado de tu distribución de Ubuntu esté encendido.

Aplica y reinicia Docker Desktop si es necesario.

Paso 2: Crear el proyecto Laravel 12 desde WSL
Regla de oro: Nunca crees ni guardes proyectos de WSL en el disco de Windows (ej. /mnt/c/Users/... o C:\...). El rendimiento será terrible. Siempre trabaja dentro del sistema de archivos nativo de Linux (/home/...).

Abre tu terminal de Ubuntu (puedes buscar "Ubuntu" en el menú de inicio de Windows 11).

Ve a tu directorio de usuario en Linux:

Bash
cd ~
Ejecuta el script oficial de Laravel (esto descargará la última versión, Laravel 12):

Bash
curl -s "https://laravel.build/gym-backend" | bash
(Te pedirá tu contraseña de Ubuntu en algún momento para ajustar permisos).

Paso 3: Levantar Laravel Sail
Una vez que el script termine, entra a la carpeta e inicia los contenedores en segundo plano (-d para detached):

Bash
cd gym-backend
./vendor/bin/sail up -d
¡Boom! Laravel 12 ya está corriendo en http://localhost.

Paso 4: Configurar PhpStorm (La integración clave)
Aquí es donde muchos desarrolladores se traban. Como tu código está en WSL y PHP está dentro de un contenedor de Docker, PhpStorm necesita saber cómo encontrarlos.

1. Abrir el proyecto correctamente:

Abre PhpStorm.

Haz clic en Open.

En la barra de rutas arriba, escribe la ruta de red de WSL. Usualmente es: \\wsl$\Ubuntu\home\tu_usuario\gym-backend (o \\wsl.localhost\Ubuntu\home\...).

Selecciona la carpeta y ábrela. PhpStorm te preguntará si confías en el proyecto; dile que sí.

2. Configurar el Intérprete de PHP (Para que funcione el autocompletado y los tests):

En PhpStorm, ve a File > Settings (o presiona Ctrl + Alt + S).

Navega a PHP (bajo Languages & Frameworks).

En la sección CLI Interpreter, haz clic en los tres puntos ... a la derecha.

Haz clic en el símbolo + y elige From Docker, Vagrant, VM, WSL, Remote...

Selecciona la opción Docker Compose.

En Server, elige tu conexión de Docker (suele autodetectarla).

En Configuration file(s), asegúrate de que apunte a tu archivo docker-compose.yml (PhpStorm debería detectarlo automáticamente).

En Service, selecciona laravel.test (este es el nombre oficial del contenedor que ejecuta PHP en Sail).

Haz clic en OK. PhpStorm se conectará al contenedor y detectará la versión de PHP (probablemente PHP 8.3 u 8.4 para Laravel 12). Haz clic en Apply y OK.

3. Configurar la Terminal de PhpStorm:
   Para no tener que salir de PhpStorm al ejecutar comandos:

Ve a Settings > Tools > Terminal.

En Shell path, escribe: wsl.exe -d Ubuntu

Ahora, cuando abras la terminal integrada (Alt + F12), estarás directamente en tu entorno de Linux listo para usar ./vendor/bin/sail artisan.

PRY_AUTENTICACION_MICROSERVICIO — README
1. Resumen

Microservicio de Autenticación construido con Laravel 12.
Provee registro (opcional), login y validación de tokens con Laravel Sanctum. Se conecta a MySQL y expone un endpoint para que otros microservicios validen tokens:

POST /api/register (opcional)

POST /api/login

GET /api/validate-token (protegido por auth:sanctum)

2. Requisitos

PHP 8.2+ (según requerimiento de Laravel 12)

Composer

MySQL (XAMPP, MariaDB o servidor remoto)

Git

(Opcional) Docker / docker-compose

Postman o similar para pruebas

3. Estructura del repositorio
PRY_AUTENTICACION_MICROSERVICIO/
├─ app/
│  └─ Http/Controllers/AuthController.php
├─ app/Models/User.php
├─ routes/
│  └─ api.php
├─ database/
│  └─ migrations/
├─ .env
├─ composer.json
└─ README.md

4. Instalación (local)

Desde la carpeta donde quieras clonar:

git clone <TU_REPO_URL> PRY_AUTENTICACION_MICROSERVICIO
cd PRY_AUTENTICACION_MICROSERVICIO
composer install
cp .env.example .env
php artisan key:generate

5. Configurar .env (MySQL)

Edita .env con los datos de tu MySQL (XAMPP por ejemplo):

APP_NAME=AuthService
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=auth_db
DB_USERNAME=tu_usuario_mysql
DB_PASSWORD=tu_contraseña_mysql

LOG_CHANNEL=stack
LOG_LEVEL=debug


IMPORTANTE: si usas XAMPP asegúrate que MySQL esté iniciado y que la base auth_db exista. Puedes crearla con:

CREATE DATABASE auth_db;


(o desde phpMyAdmin).

6. Instalación y configuración de Sanctum

Si no se instaló aún:

composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate


php artisan migrate debe crear las tablas users y personal_access_tokens (además de otras migraciones que tengas).

7. Modelo User

El app/Models/User.php debe usar el trait HasApiTokens:

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    ...
}


Esto permite emitir tokens con $user->createToken(...)->plainTextToken.

8. Controlador y rutas clave

app/Http/Controllers/AuthController.php (resumen)

register(Request $r) — validar y crear usuario (opcional).

login(Request $r) — validar credenciales y emitir token.

validateToken(Request $r) — devuelve valid: true y user (protegido por auth:sanctum).

Rutas (routes/api.php):

Route::post('/register',[AuthController::class,'register']); // opcional
Route::post('/login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->get('/validate-token',[AuthController::class,'validateToken']);

9. Comandos importantes

Migraciones:

php artisan migrate


Limpiar caché de config (si Laravel no lee .env):

php artisan config:clear
php artisan cache:clear
php artisan optimize:clear


Verificar driver DB en tinker:

php artisan tinker
>>> DB::connection()->getDriverName(); // debe devolver "mysql"

10. Cómo ejecutar (desarrollo)

Opción A — php artisan serve (recomendado para desarrollo rápido):

php artisan serve --host=127.0.0.1 --port=8000
# servicio disponible en http://127.0.0.1:8000


Opción B — servir con Apache (XAMPP):

Coloca el proyecto dentro de htdocs (o crea Virtual Host).

Asegúrate de que public/ sea accesible desde Apache.

Reinicia Apache si es necesario.

11. Pruebas en Postman (ejemplos)

Registro (opcional)

POST http://127.0.0.1:8000/api/register
Body (JSON):
{
  "name": "Juan Perez",
  "email": "juan@example.com",
  "password": "123456"
}


Login

POST http://127.0.0.1:8000/api/login
Body (JSON):
{
  "email": "juan@example.com",
  "password": "123456"
}


Respuesta:

{
  "message": "Login correcto",
  "token_type": "Bearer",
  "token": "eyJ..."
}


Validar token

GET http://127.0.0.1:8000/api/validate-token
Header: Authorization: Bearer <TOKEN_AQUI>


Respuesta esperada:

{ "valid": true, "user": { "id":1, "name":"Juan Perez", "email":"juan@example.com" } }


12. Git y entrega

Ejemplo mínimo:

git init
git add .
git commit -m "Auth microservice: initial"
git branch -M main
git remote add origin git@github.com:TU_USUARIO/PRY_AUTENTICACION_MICROSERVICIO.git
git push -u origin main


Incluye en el repo:

Código fuente

.env.example (sin credenciales reales)

postman_collection.json (opcional)

README.md (este archivo)

13. Archivos útiles (ejemplo .env.example)
APP_NAME=AuthService
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=auth_db
DB_USERNAME=root
DB_PASSWORD=

LOG_CHANNEL=stack
LOG_LEVEL=debug
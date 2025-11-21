# ============================================
# README.md — PRY_AUTENTICACION_MICROSERVICIO
# ============================================

# 1. Resumen (solo comandos simbólicos)
echo "Microservicio de Autenticación con Laravel 12 + Sanctum"

# Endpoints:
echo "POST /api/register"
echo "POST /api/login"
echo "GET  /api/validate-token (auth:sanctum)"

# 2. Requisitos
php -v
composer -V
mysql --version
git --version

# 3. Estructura del repositorio (generada automáticamente por Laravel)
tree -L 3

# 4. Instalación local
git clone <TU_REPO_URL> PRY_AUTENTICACION_MICROSERVICIO
cd PRY_AUTENTICACION_MICROSERVICIO
composer install
cp .env.example .env
php artisan key:generate

# 5. Configurar .env (MySQL)
sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
sed -i 's/DB_HOST=.*/DB_HOST=127.0.0.1/' .env
sed -i 's/DB_PORT=.*/DB_PORT=3306/' .env
sed -i 's/DB_DATABASE=.*/DB_DATABASE=auth_db/' .env
sed -i 's/DB_USERNAME=.*/DB_USERNAME=tu_usuario_mysql/' .env
sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=tu_contraseña_mysql/' .env

# Crear base de datos si no existe
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS auth_db;"

# 6. Instalar Sanctum
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

# 7. Verificar modelo User usando HasApiTokens
grep -R "HasApiTokens" app/Models/User.php

# 8. Controlador y rutas clave
grep -R "register" app/Http/Controllers/AuthController.php
grep -R "login" app/Http/Controllers/AuthController.php
grep -R "validateToken" app/Http/Controllers/AuthController.php

grep -R "validate-token" routes/api.php

# 9. Comandos importantes
php artisan migrate
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear

# Ver driver MySQL
php artisan tinker <<< "DB::connection()->getDriverName();"

# 10. Servir proyecto
php artisan serve --host=127.0.0.1 --port=8000

# 11. Pruebas API (curl)
# Registro
curl -X POST http://127.0.0.1:8000/api/register \
-H "Content-Type: application/json" \
-d '{"name":"Juan Perez","email":"juan@example.com","password":"123456"}'

# Login
curl -X POST http://127.0.0.1:8000/api/login \
-H "Content-Type: application/json" \
-d '{"email":"juan@example.com","password":"123456"}'

# Validar token
curl -X GET http://127.0.0.1:8000/api/validate-token \
-H "Authorization: Bearer TOKEN_AQUI"

# 12. Git y entrega
git init
git add .
git commit -m "Auth microservice: initial"
git branch -M main
git remote add origin git@github.com:TU_USUARIO/PRY_AUTENTICACION_MICROSERVICIO.git
git push -u origin main

# 13. Archivo .env.example (generado via comandos)
cat > .env.example << 'EOF'
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
EOF
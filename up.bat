@echo off
REM Script para construir y levantar la aplicación Docker.

echo 🚀 Construyendo y levantando los contenedores de Docker...

REM 1. Copia el .env si no existe
if not exist .env (
    echo Creando archivo .env desde .env.example...
    copy .env.example .env
    echo ¡Hecho! Por favor, revisa y edita el .env si necesitas contraseñas.
)

REM 2. Levanta la aplicación
docker-compose up -d --build

REM 3. Muestra el estado
echo ✅ ¡Aplicación iniciada!
docker-compose ps
echo 👉 Accede a la aplicacion en http://localhost:8080/public/index.php
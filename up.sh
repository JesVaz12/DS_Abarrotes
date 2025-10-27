#!/bin/bash
#
# Script para construir y levantar la aplicación Docker.
#

# Muestra un mensaje al usuario
echo "🚀 Construyendo y levantando los contenedores de Docker..."

# 1. Copia el .env si no existe
if [ ! -f .env ]; then
    echo "Creando archivo .env desde .env.example..."
    cp .env.example .env
    echo "¡Hecho! Por favor, revisa y edita el .env si necesitas contraseñas."
fi

# 2. Levanta la aplicación
docker-compose up -d --build

# 3. Muestra el estado
echo "✅ ¡Aplicación iniciada!"
docker-compose ps
echo "👉 Accede a la aplicación en http://localhost:8080/public/index.php"
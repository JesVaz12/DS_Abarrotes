#!/bin/bash
#
# Script para detener y eliminar TODOS los datos del proyecto.
#

echo "🛑 Deteniendo y eliminando contenedores..."

# El flag -v es la parte de "desinstalación", ya que borra 
# el volumen 'db_data' que contiene la base de datos.
docker-compose down -v

echo "🗑️ ¡Proyecto detenido y datos eliminados!"
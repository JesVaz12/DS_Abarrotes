@echo off
REM Script para detener y eliminar TODOS los datos del proyecto.

echo 🛑 Deteniendo y eliminando contenedores...

REM El flag -v es la parte de "desinstalacion", ya que borra 
REM el volumen 'db_data' que contiene la base de datos.
docker-compose down -v

echo 🗑️ ¡Proyecto detenido y datos eliminados!
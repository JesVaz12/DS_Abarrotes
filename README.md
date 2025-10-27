# Proyecto Abarrotes AM (DS_Abarrotes)

Este repositorio contiene el código fuente para un sistema de gestión de inventario, "Abarrotes AM". La aplicación está completamente dockerizada para un fácil despliegue y un entorno de desarrollo consistente.

## Estructura del Repositorio

* **/app**: Contiene la aplicación web PHP.
    * **/public**: Punto de entrada de la aplicación (index.php).
    * **/src**: Lógica de la aplicación (controladores, configuración).
    * **/templates**: Vistas y layouts de la aplicación.
    * **/database**: El dump SQL de la base de datos.
    * **/vendor**: Librerías de terceros (FPDF).
* **/data_science**: Contiene notebooks y análisis de ciencia de datos relacionados.
* **/docker**: Contiene las configuraciones de Docker (Dockerfile y config de Apache).
* **docker-compose.yml**: Archivo principal de orquestación de Docker.

---

## Requisitos

Para ejecutar este proyecto, solo necesitarás tener instalados:

* [Git](https://git-scm.com/)
* [Docker Desktop](https://www.docker.com/products/docker-desktop/)

---

## 🚀 Instalación y Puesta en Marcha

Sigue estos pasos para levantar la aplicación localmente:

**1. Clonar el Repositorio**

```bash
git clone [https://github.com/JesVaz12/DS_Abarrotes.git](https://github.com/JesVaz12/DS_Abarrotes.git)
cd DS_Abarrotes
````

**2. Configurar el Entorno**

Copia el archivo de ejemplo `.env.example` para crear tu propio archivo de configuración `.env`.

```bash
cp .env.example .env
```

Abre el archivo `.env` con un editor de texto y asigna contraseñas a `DB_PASSWORD` y `DB_ROOT_PASSWORD`. (Para desarrollo local, puedes dejarlas vacías si así lo deseas).

**3. Levantar los Contenedores**

Usa Docker Compose para construir y levantar todos los servicios (Servidor Web y Base de Datos).

```bash
docker-compose up -d --build
```

  * `up`: Inicia los contenedores.
  * `-d`: Modo *detached* (se ejecuta en segundo plano).
  * `--build`: Fuerza la reconstrucción de la imagen la primera vez.

> La base de datos `itemcontrol` se creará e importará automáticamente desde el archivo `app/database/itemcontrol-2.sql` la primera vez que se inicie el contenedor.

-----

## URLs de Acceso

Una vez que los contenedores estén corriendo, podrás acceder a la aplicación:

  * **Página de Inicio (Login):** [http://localhost:8080/public/index.php](https://www.google.com/search?q=http://localhost:8080/public/index.php)

-----

## Comandos Útiles de Docker

**Ver logs (registros) en tiempo real:**

```bash
docker-compose logs -f
```

**Detener la aplicación:**

```bash
docker-compose down
```

**Reiniciar la aplicación:**

```bash
docker-compose restart
```

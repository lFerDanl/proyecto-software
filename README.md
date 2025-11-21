# Nombre del Proyecto

> Breve descripción del proyecto (en una línea). Ejemplo: Un sistema de gestión de cursos en línea para la administración de usuarios, suscripciones, y renovaciones.

## Tabla de Contenidos
- [Descripción](#descripción)
- [Características](#características)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Uso](#uso)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Contribución](#contribución)
- [Licencia](#licencia)

---

## Descripción

Este proyecto es un sistema de gestión diseñado para administrar usuarios, suscripciones, cursos y renovaciones en una plataforma educativa en línea. Permite que los administradores gestionen las renovaciones de las suscripciones, los métodos de pago y las cuentas de usuario.

## Características

- CRUD de usuarios, roles, suscripciones y renovaciones.
- Gestión de suscripciones y renovaciones con diferentes métodos de pago.
- Roles y permisos diferenciados para administradores, autores y clientes.
- Uso de migraciones y seeders para configurar la base de datos.
- Interfaz de usuario con soporte para modales de edición y creación.

## Instalación

Para ejecutar este proyecto de manera local, sigue los pasos:
no se olviden de actualizar el .env para la base de datos
1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/tu-usuario/nombre-del-proyecto.git
   cd nombre-del-proyecto
2. **Instalar dependencias:**
  ```bash
  composer install
    npm install

3. **Configurar el archivo .env:**
  ```bash
  cp .env.example .env

4. **Generar la clave de la aplicación:**
  ```bash
  php artisan key:generate
5. **Ejecutar las migraciones:**
  ```bash
  php artisan migrate
6. **Configurar la base de datos: Asegúrate de que los detalles de la base de datos en el archivo .env sean correctos. Luego, ejecuta las migraciones y seeders:**
  ```bash
  php artisan migrate --seed

7. **Ejecutar el servidor:**
  ```bash
  php artisan serve

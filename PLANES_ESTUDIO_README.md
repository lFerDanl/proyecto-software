# Sistema de Planes de Estudio por Nivel de Suscripción

Este sistema permite a los usuarios generar planes de estudio personalizados según su nivel de suscripción:

- **Sin suscripción**: Solo pueden generar planes de nivel Principiante
- **Con suscripción activa**: Pueden generar planes de nivel Principiante, Intermedio y Avanzado

## Instalación

Para instalar esta funcionalidad, sigue estos pasos:

1. Ejecuta el archivo `actualizar-planes.bat` o ejecuta manualmente:
   ```
   php artisan migrate
   ```

2. Esto agregará la columna `nivel` a la tabla `plan_estudios` para almacenar el nivel de cada plan generado.

## Uso

1. Accede a la sección "Crear Plan de Estudio"
2. Selecciona un curso de la lista de cursos comprados
3. Según tu estado de suscripción, verás diferentes opciones:
   - **Sin suscripción**: Solo verás el botón "Generar Plan Principiante"
   - **Con suscripción activa**: Verás los tres botones: "Generar Plan Principiante", "Generar Plan Intermedio" y "Generar Plan Avanzado"

## Características

- **Middleware de control de acceso**: Verifica automáticamente si el usuario tiene permiso para acceder a cada nivel de plan
- **Interfaz adaptativa**: Muestra solo las opciones disponibles según el estado de suscripción
- **Planes personalizados**: Cada nivel genera contenido específico adaptado a la dificultad correspondiente
- **Diseño visual distintivo**: Cada nivel tiene su propio esquema de colores e iconos para fácil identificación

## Estructura técnica

- **Middleware**: `CheckPlanAccess` controla el acceso a los diferentes niveles
- **Modelo Usuario**: Incluye métodos `tieneSuscripcionActiva()` y `getSuscripcionActiva()`
- **Rutas**: Protegidas con middleware para cada nivel
- **Vistas**: Adaptativas según el estado de suscripción del usuario

## Soporte

Si tienes problemas con esta funcionalidad, asegúrate de:

1. Haber ejecutado correctamente la migración
2. Tener una suscripción activa para acceder a los niveles Intermedio y Avanzado
3. Tener al menos un curso comprado para poder generar planes de estudio

# Empresa de Seguridad Privada O

Proyecto del curso Lenguaje de Programación II.

## Módulos

- Usuarios y vigilantes
- Puestos de vigilancia
- Turnos y asignaciones
- Incidencias
- Seguimientos
- Notificaciones

## Estructura

- `app/modelos/`: consultas y operaciones de base de datos
- `app/controladores/`: comunicación entre modelos y vistas
- `app/vistas/`: formularios y tablas
- `app/config/`: conexión a MySQL
- `database/script.sql`: tablas de la base de datos
- `docs/`: diagramas, bitácora y evidencias

## Ejecutar localmente

1. Iniciar Apache y MySQL con XAMPP.
2. Importar `database/script.sql` en phpMyAdmin.
3. Colocar el proyecto en `htdocs`.
4. Abrir `http://localhost/EMPRESA_DE_SEGURIDAD_O/`.

Las vistas son prototipos. Las operaciones de base de datos necesitan probarse con MySQL antes de entregar.

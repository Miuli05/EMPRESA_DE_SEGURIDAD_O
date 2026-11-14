# Catálogo de clases y funciones de base de datos

Estado: **métodos implementados en PHP, pendientes de pruebas reales con MySQL y capturas.** No confundir código escrito con funcionalidades ya verificadas.

| Clase | Tabla | Función | Operación | Responsable |
|---|---|---|---|---|
| Usuario | usuarios | listar() | SELECT | Por asignar |
| Usuario | usuarios | buscarPorId(int) | SELECT | Por asignar |
| Vigilante | vigilantes | listar() | SELECT | Por asignar |
| Vigilante | vigilantes | buscarPorId(int) | SELECT | Por asignar |
| Vigilante | vigilantes | actualizarEstado(int, string) | UPDATE | Por asignar |
| Puesto | puestos | listar() | SELECT | Por asignar |
| Puesto | puestos | buscarPorId(int) | SELECT | Por asignar |
| Puesto | puestos | registrar(string, string, int) | INSERT | Por asignar |
| Turno | turnos | listar() | SELECT | Por asignar |
| Turno | turnos | buscarPorId(int) | SELECT | Por asignar |
| Turno | turnos | listarPorFecha(string) | SELECT | Por asignar |
| Turno | turnos | registrar(int, string, string, ?int) | SELECT, INSERT | Por asignar |
| Asignacion | asignaciones | listar() | SELECT | Por asignar |
| Asignacion | asignaciones | buscarPorId(int) | SELECT | Por asignar |
| Asignacion | asignaciones | buscarPorTurno(int) | SELECT | Por asignar |
| Asignacion | asignaciones | registrar(int, int, ?int) | SELECT, INSERT, UPDATE | Por asignar |
| Incidencia | incidencias | listar() | SELECT | Por asignar |
| Incidencia | incidencias | buscarPorId(int) | SELECT | Por asignar |
| Incidencia | incidencias | registrar(int, int, string, string) | SELECT, INSERT | Por asignar |
| Seguimiento | seguimientos | listar() | SELECT | Por asignar |
| Seguimiento | seguimientos | buscarPorId(int) | SELECT | Por asignar |
| Seguimiento | seguimientos | listarPorIncidencia(int) | SELECT | Por asignar |
| Seguimiento | seguimientos | registrar(int, int, string) | INSERT, UPDATE | Por asignar |
| Notificacion | notificaciones | listar() | SELECT | Por asignar |
| Notificacion | notificaciones | buscarPorId(int) | SELECT | Por asignar |
| Notificacion | notificaciones | listarPendientes(int) | SELECT | Por asignar |
| Notificacion | notificaciones | marcarLeida(int) | UPDATE | Por asignar |



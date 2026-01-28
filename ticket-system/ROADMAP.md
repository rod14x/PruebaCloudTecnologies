# 🗺️ Roadmap del Sistema de Gestión de Tickets

## ✅ Funcionalidades Implementadas

### 1. **Autenticación y Autorización** ✅
- [x] Registro de usuarios (auto-asignación rol Empleado)
- [x] Login con validación
- [x] Recuperación de contraseña (código por email)
- [x] Reset de contraseña
- [x] Logout con invalidación de sesión
- [x] Middleware de autenticación
- [x] Middleware de roles (admin, employee)
- [x] Protección de rutas por rol
- [x] Verificación en componentes Livewire

**Archivos clave:**
- `app/Livewire/Auth/*`
- `app/Http/Middleware/EnsureUserIsAdmin.php`
- `app/Http/Middleware/EnsureUserIsEmployee.php`
- `app/Enums/RolUsuario.php`

---

### 2. **Gestión de Tickets (Empleados)** ✅
- [x] Crear tickets con:
  - Título, descripción
  - Categoría (select dinámico)
  - Prioridad (Baja, Media, Alta)
  - Evidencia fotográfica (opcional, hasta 5MB)
- [x] Ver mis tickets (lista paginada)
- [x] Ver detalle de ticket propio
- [x] Ver historial de cambios de estado
- [x] Ver comentarios del ticket
- [x] Upload de archivos adjuntos
- [x] Vista previa de imágenes

**Archivos clave:**
- `app/Livewire/Tickets/CreateTicket.php`
- `app/Livewire/Tickets/MyTickets.php`
- `app/Livewire/Tickets/TicketShow.php`
- `app/Actions/Tickets/CreateTicketAction.php`

---

### 3. **Gestión de Tickets (Administradores)** ✅
- [x] Dashboard administrativo con:
  - KPI: Total de tickets
  - KPI: Tickets abiertos (Pendiente + En Proceso)
  - KPI: Tickets cerrados (Resueltos)
  - Gráfico de barras: Abiertos vs Cerrados
  - Gráfico de pie: Distribución por prioridad
- [x] Ver todos los tickets (con filtros)
- [x] Filtrar por:
  - Búsqueda por texto
  - Estado (Pendiente, En Proceso, Resuelto)
  - Prioridad (Baja, Media, Alta)
  - Categoría
- [x] Ver detalle de cualquier ticket
- [x] Cambiar estado de tickets
- [x] Ver historial completo

**Archivos clave:**
- `app/Livewire/Admin/Dashboard.php`
- `app/Livewire/Admin/AdminTickets.php`
- `app/Livewire/Tickets/CambiarEstado.php`
- `resources/views/livewire/admin/dashboard.blade.php` (con Chart.js)

---

### 4. **Sistema de Comentarios** ✅
- [x] Agregar comentarios a tickets
- [x] Ver historial de comentarios
- [x] Identificación del autor
- [x] Timestamps de comentarios

**Archivos clave:**
- `app/Models/Comentario.php`
- Vista integrada en `TicketShow`

---

### 5. **Gestión de Archivos Adjuntos** ✅
- [x] Upload de evidencias al crear ticket
- [x] Almacenamiento en `storage/app/public/evidencias`
- [x] Enlace simbólico configurado
- [x] Validación de tipo y tamaño de archivo
- [x] Vista previa de imágenes

**Archivos clave:**
- `app/Models/ArchivoAdjunto.php`
- Storage configurado en `config/filesystems.php`

---

### 6. **Historial de Estados** ✅
- [x] Registro automático de cambios de estado
- [x] Tracking de usuario que realizó el cambio
- [x] Timestamps de cada cambio
- [x] Visualización en timeline

**Archivos clave:**
- `app/Models/HistorialEstado.php`
- `app/Observers/TicketObserver.php`

---

### 7. **Arquitectura de Datos** ✅
- [x] Repository Pattern implementado
- [x] Service Layer para lógica de negocio
- [x] Action Pattern para operaciones complejas
- [x] Observer Pattern para eventos
- [x] Query Scopes reutilizables
- [x] Enums con comportamiento

**Archivos clave:**
- `app/Repositories/TicketRepository.php`
- `app/Services/TicketService.php`
- `app/Actions/Tickets/*`
- `app/Observers/TicketObserver.php`

---

### 8. **UI/UX** ✅
- [x] Layout responsivo con Tailwind CSS
- [x] Componentes Livewire con Alpine.js
- [x] Indicadores de estado con colores
- [x] Badges de prioridad
- [x] Notificaciones toast
- [x] Navegación condicional por rol
- [x] Chart.js para visualización de datos

---

### 9. **Seguridad** ✅
- [x] Protección de rutas con middleware
- [x] Verificación de permisos en componentes
- [x] UI condicional según rol
- [x] Validación de acceso a tickets propios
- [x] CSRF protection
- [x] Password hashing
- [x] Prevención de acceso directo por URL

---

## 🚧 Funcionalidades Pendientes

### 10. **Asignación de Tickets** ⏳
- [ ] Asignar ticket a administrador específico
- [ ] Ver tickets asignados a mí
- [ ] Reasignar tickets
- [ ] Filtro por ticket asignado
- [ ] Notificación al asignar

**Prioridad:** Alta  
**Complejidad:** Media  
**Archivos a modificar:**
- Migration: agregar columna `asignado_a` en `tickets`
- `app/Livewire/Admin/AdminTickets.php` (agregar acción de asignar)
- `app/Services/TicketService.php` (método `assignTicket`)

---

### 11. **Notificaciones por Email** ⏳
- [ ] Enviar email al crear ticket
- [ ] Notificar cambio de estado
- [ ] Notificar nuevo comentario
- [ ] Notificar asignación
- [ ] Templates de email personalizados

**Prioridad:** Alta  
**Complejidad:** Media  
**Archivos a crear:**
- `app/Mail/TicketCreated.php`
- `app/Mail/TicketStatusChanged.php`
- `app/Mail/NewCommentAdded.php`
- `resources/views/emails/tickets/*`
- Configurar SMTP en `.env`

**Nota:** Utilizar Laravel Notifications o Mailable classes

---

### 12. **Exportar Reportes** ⏳
- [ ] Exportar tickets a Excel
- [ ] Exportar tickets a CSV
- [ ] Exportar estadísticas
- [ ] Filtrar por rango de fechas
- [ ] Incluir gráficos en PDF

**Prioridad:** Media  
**Complejidad:** Media  
**Paquetes sugeridos:**
- `maatwebsite/excel` para Excel/CSV
- `barryvdh/laravel-dompdf` para PDF

**Archivos a crear:**
- `app/Exports/TicketsExport.php`
- `app/Livewire/Admin/ReportGenerator.php`

---

### 13. **Sistema de Etiquetas/Tags** ⏳
- [ ] Crear tabla `tags`
- [ ] Relación many-to-many con tickets
- [ ] Asignar múltiples tags a un ticket
- [ ] Filtrar por tags
- [ ] Sugerencias de tags

**Prioridad:** Baja  
**Complejidad:** Baja  
**Archivos a crear:**
- Migration: `create_tags_table.php`
- Migration: `create_ticket_tag_table.php`
- `app/Models/Tag.php`
- Agregar UI en `CreateTicket` y `TicketShow`

---

### 14. **SLA y Alertas** ⏳
- [ ] Definir tiempo de resolución por prioridad
- [ ] Calcular tiempo transcurrido
- [ ] Alertas de tickets próximos a vencer
- [ ] Indicador visual de SLA
- [ ] Notificaciones de vencimiento

**Prioridad:** Media  
**Complejidad:** Alta  
**Archivos a crear:**
- `app/Services/SLAService.php`
- Migration: agregar `sla_vencimiento_en` en `tickets`
- Comando artisan para verificar SLA
- Dashboard widget con tickets próximos a vencer

**Cálculo sugerido:**
- Alta: 24 horas
- Media: 48 horas
- Baja: 72 horas

---

### 15. **Dashboard de Empleados** ⏳
- [ ] Vista de mis estadísticas
- [ ] Mis tickets por estado
- [ ] Mis tickets por prioridad
- [ ] Tiempo promedio de resolución
- [ ] Historial de actividad

**Prioridad:** Baja  
**Complejidad:** Baja  
**Archivos a crear:**
- `app/Livewire/Employee/Dashboard.php`
- `resources/views/livewire/employee/dashboard.blade.php`
- Agregar ruta en `routes/web.php`

---

### 16. **Búsqueda Avanzada** ⏳
- [ ] Búsqueda por múltiples criterios
- [ ] Búsqueda por rango de fechas
- [ ] Búsqueda por asignado
- [ ] Búsqueda por autor
- [ ] Guardar búsquedas favoritas

**Prioridad:** Baja  
**Complejidad:** Media  
**Archivos a modificar:**
- `app/Livewire/Admin/AdminTickets.php` (expandir filtros)
- `app/Repositories/TicketRepository.php` (agregar métodos de búsqueda)

---

### 17. **Sistema de Priorización Automática** ⏳
- [ ] Algoritmo de priorización basado en:
  - Tiempo transcurrido
  - Categoría
  - Palabras clave en descripción
- [ ] Sugerencia de prioridad al crear ticket
- [ ] Re-priorización automática

**Prioridad:** Baja  
**Complejidad:** Alta  
**Archivos a crear:**
- `app/Services/PrioritizationService.php`
- Integrar en `CreateTicketAction`

---

### 18. **Adjuntar Múltiples Archivos** 🔄 (Parcial)
- [x] Un archivo al crear ticket
- [ ] Múltiples archivos al crear
- [ ] Agregar archivos después de crear
- [ ] Eliminar archivos adjuntos
- [ ] Vista previa de PDFs

**Prioridad:** Media  
**Complejidad:** Baja  
**Archivos a modificar:**
- `app/Livewire/Tickets/CreateTicket.php` (soporte múltiple)
- `app/Livewire/Tickets/TicketShow.php` (agregar/eliminar)

---

### 19. **Sistema de Permisos Granular** ⏳
- [ ] Más roles (Técnico, Supervisor)
- [ ] Permisos específicos por rol
- [ ] Middleware de permisos
- [ ] UI condicional por permisos

**Prioridad:** Baja  
**Complejidad:** Alta  
**Paquetes sugeridos:**
- `spatie/laravel-permission`

---

### 20. **Notificaciones en Tiempo Real** ⏳
- [ ] WebSocket con Laravel Reverb
- [ ] Notificaciones push
- [ ] Indicador de notificaciones no leídas
- [ ] Centro de notificaciones
- [ ] Marcar como leído

**Prioridad:** Media  
**Complejidad:** Alta  
**Nota:** Laravel Reverb ya está instalado
**Archivos a crear:**
- `app/Events/TicketUpdated.php`
- `app/Livewire/Notifications/NotificationCenter.php`
- Configurar broadcasting en `.env`

---

### 21. **API REST** ⏳
- [ ] Endpoints para CRUD de tickets
- [ ] Autenticación con Sanctum
- [ ] Documentación con Swagger/OpenAPI
- [ ] Rate limiting
- [ ] Versionado de API

**Prioridad:** Baja  
**Complejidad:** Media  
**Archivos a crear:**
- `routes/api.php`
- `app/Http/Controllers/Api/TicketController.php`
- `app/Http/Resources/TicketResource.php`

---

### 22. **Sistema de Encuestas de Satisfacción** ⏳
- [ ] Encuesta al cerrar ticket
- [ ] Calificación (1-5 estrellas)
- [ ] Comentario de satisfacción
- [ ] Dashboard de satisfacción
- [ ] Reportes de calidad

**Prioridad:** Baja  
**Complejidad:** Media  
**Archivos a crear:**
- Migration: `create_encuestas_table.php`
- `app/Models/Encuesta.php`
- `app/Livewire/Tickets/RateTicket.php`

---

### 23. **Modo Oscuro** ⏳
- [ ] Toggle de tema
- [ ] Persistencia de preferencia
- [ ] Clases Tailwind dark mode
- [ ] Ajuste de gráficos

**Prioridad:** Muy Baja  
**Complejidad:** Baja  
**Archivos a modificar:**
- `tailwind.config.js` (agregar darkMode)
- Layout principal
- Todos los componentes con clases dark:*

---

### 24. **Testing** ⏳
- [ ] Unit tests para Services
- [ ] Unit tests para Actions
- [ ] Unit tests para Repositories
- [ ] Feature tests para componentes Livewire
- [ ] Feature tests para rutas
- [ ] Test de integración

**Prioridad:** Alta (antes de producción)  
**Complejidad:** Media  
**Archivos a crear:**
- `tests/Unit/Services/TicketServiceTest.php`
- `tests/Feature/Livewire/CreateTicketTest.php`
- `tests/Feature/Livewire/AdminTicketsTest.php`

---

## 📊 Resumen de Estado

### Por Prioridad:
- **Alta:** 3 pendientes (Asignación, Emails, Testing)
- **Media:** 5 pendientes (Reportes, SLA, Adjuntos múltiples, Notificaciones real-time, API)
- **Baja:** 6 pendientes (Tags, Dashboard empleados, Búsqueda avanzada, Priorización, Permisos, Encuestas)
- **Muy Baja:** 1 pendiente (Modo oscuro)

### Por Complejidad:
- **Baja:** 5 funcionalidades
- **Media:** 8 funcionalidades
- **Alta:** 4 funcionalidades

### Progreso Global:
- ✅ **Implementado:** 9 módulos principales (60%)
- ⏳ **Pendiente:** 15 funcionalidades (40%)

---

## 🎯 Recomendación de Orden de Implementación

### Fase 1 - Funcionalidad Core (Próximas 2 semanas)
1. **Asignación de Tickets** (Alta prioridad, media complejidad)
2. **Notificaciones por Email** (Alta prioridad, media complejidad)

### Fase 2 - Mejoras de UX (Próximas 2-3 semanas)
3. **Adjuntar Múltiples Archivos** (Media prioridad, baja complejidad)
4. **Exportar Reportes** (Media prioridad, media complejidad)
5. **Dashboard de Empleados** (Baja prioridad, baja complejidad)

### Fase 3 - Funcionalidades Avanzadas (1 mes)
6. **SLA y Alertas** (Media prioridad, alta complejidad)
7. **Notificaciones en Tiempo Real** (Media prioridad, alta complejidad)
8. **Sistema de Etiquetas/Tags** (Baja prioridad, baja complejidad)

### Fase 4 - Pre-Producción (2 semanas)
9. **Testing Completo** (Alta prioridad, media complejidad)
10. **Sistema de Permisos Granular** (Baja prioridad, alta complejidad) - Si es necesario

### Fase 5 - Post-Lanzamiento (Opcional)
11. **API REST** (Baja prioridad, media complejidad)
12. **Búsqueda Avanzada** (Baja prioridad, media complejidad)
13. **Encuestas de Satisfacción** (Baja prioridad, media complejidad)
14. **Priorización Automática** (Baja prioridad, alta complejidad)
15. **Modo Oscuro** (Muy baja prioridad, baja complejidad)

---

## 📝 Notas Adicionales

### Decisiones Técnicas Tomadas:
- ❌ **Archivar Tickets**: Descartado por complejidad en lógica de estadísticas
- ✅ **Middleware por Rol**: Implementado en lugar de paquetes de permisos
- ✅ **Chart.js**: Elegido para visualización de datos
- ✅ **Laravel Reverb**: Instalado para futuras notificaciones en tiempo real

### Próximas Decisiones Requeridas:
- ¿Implementar soft deletes en tickets?
- ¿Permitir editar tickets después de crearlos?
- ¿Implementar sistema de tickets relacionados/dependientes?
- ¿Agregar campo de "Solución" al resolver ticket?
- ¿Implementar sistema de sub-tickets o tareas?

---

**Última actualización:** 27 de enero de 2026

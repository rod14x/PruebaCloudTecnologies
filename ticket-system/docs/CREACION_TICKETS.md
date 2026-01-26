# Sistema de Creación de Tickets

## Descripción General

Funcionalidad completa para que los usuarios autenticados puedan crear tickets de incidentes con la posibilidad de adjuntar evidencia fotográfica.

## Características Implementadas

### 1. Formulario de Creación de Tickets
- **Componente**: `App\Livewire\Tickets\CreateTicket`
- **Ruta**: `/tickets/create` (protegida con middleware `auth` y `verified`)
- **Layout**: `components.layouts.app` (layout autenticado)

### 2. Campos del Formulario

#### Título
- **Tipo**: Texto
- **Requerido**: Sí
- **Máximo**: 255 caracteres
- **Validación**: `required|string|max:255`

#### Categoría
- **Tipo**: Select (dropdown)
- **Requerido**: Sí
- **Opciones**: Cargadas desde tabla `categorias`
- **Validación**: `required|exists:categorias,id`

#### Prioridad
- **Tipo**: Radio buttons con indicadores de color
- **Requerido**: Sí
- **Opciones**:
  - **Baja (0)**: Indicador azul (`bg-blue-500`)
  - **Media (1)**: Indicador amarillo/ámbar (`bg-amber-500`)
  - **Alta (2)**: Indicador rojo (`bg-red-500`)
- **Validación**: `required|in:0,1,2`

#### Descripción
- **Tipo**: Textarea (5 filas)
- **Requerido**: Sí
- **Validación**: `required|string`

#### Evidencia
- **Tipo**: File upload (imágenes)
- **Requerido**: No (opcional)
- **Formatos aceptados**: PNG, JPG, GIF
- **Tamaño máximo**: 5MB (5120KB)
- **Validación**: `nullable|image|max:5120`
- **Características especiales**:
  - Vista previa de imagen antes de enviar
  - Indicador de carga durante el upload
  - Nombre temporal generado automáticamente

### 3. Proceso de Creación

#### Paso 1: Validación
```php
$this->validate();
```

#### Paso 2: Crear Ticket
```php
$ticket = Ticket::create([
    'titulo' => $this->titulo,
    'descripcion' => $this->descripcion,
    'categoria_id' => $this->categoria_id,
    'prioridad' => (int) $this->prioridad,
    'estado' => EstadoTicket::PENDIENTE, // Estado inicial
    'usuario_id' => auth()->id(),
]);
```

#### Paso 3: Guardar Evidencia (si existe)
```php
if ($this->evidencia) {
    $filename = 'ticket_' . $ticket->id . '_' . time() . '.' . $this->evidencia->getClientOriginalExtension();
    $path = $this->evidencia->storeAs('evidencias', $filename, 'public');

    ArchivoAdjunto::create([
        'ticket_id' => $ticket->id,
        'nombre_archivo' => $this->evidencia->getClientOriginalName(),
        'ruta_archivo' => $path,
        'tamano' => $this->evidencia->getSize(),
    ]);
}
```

#### Paso 4: Redirección
- Muestra mensaje de éxito en sesión
- Redirecciona al dashboard

## Layout Autenticado

### Componente: `components.layouts.app`

#### Header
- Logo con gradiente azul-índigo
- Navegación con 2 opciones:
  - Dashboard (activo con `bg-slate-100`)
  - Crear Ticket (activo con `bg-slate-100`)
- Información del usuario:
  - Nombre
  - Badge de rol con color:
    - **Administrador**: `bg-purple-100 text-purple-700`
    - **Empleado**: `bg-blue-100 text-blue-700`
  - Botón cerrar sesión

#### Main Content
- Contenedor con máximo ancho de 7xl
- Padding responsive
- Slot para contenido dinámico

## Almacenamiento de Archivos

### Configuración
- **Disco**: `public` (storage/app/public)
- **Directorio**: `evidencias/`
- **Enlace simbólico**: `public/storage` → `storage/app/public`
- **Comando ejecutado**: `php artisan storage:link`

### Nomenclatura de Archivos
```
ticket_{id}_{timestamp}.{extension}
```
Ejemplo: `ticket_1_1737924567.jpg`

### Tabla `archivos_adjuntos`
```php
[
    'ticket_id' => 1,
    'nombre_archivo' => 'evidencia_error.jpg', // Nombre original
    'ruta_archivo' => 'evidencias/ticket_1_1737924567.jpg', // Ruta en storage
    'tamano' => 245678, // Bytes
]
```

## Protección de Rutas

### Middleware Aplicado
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tickets/create', \App\Livewire\Tickets\CreateTicket::class)
        ->name('tickets.create');
});
```

### Restricciones
- Solo usuarios autenticados pueden acceder
- Email debe estar verificado (aunque actualmente no se usa verificación)
- Cualquier rol puede crear tickets (EMPLEADO o ADMINISTRADOR)

## Diseño UI/UX

### Colores de Prioridad
- **Baja**: Azul (`blue-500`, `blue-600`)
- **Media**: Ámbar (`amber-500`, `amber-600`)
- **Alta**: Rojo (`red-500`, `red-600`)

### Estados Visuales
- **Focus**: Ring azul en inputs (`focus:ring-blue-500`)
- **Hover**: Transiciones suaves en botones
- **Loading**: Indicadores de carga en:
  - Upload de imagen ("Cargando imagen...")
  - Submit del formulario ("Creando...")
- **Disabled**: Estados deshabilitados durante procesos

### Componentes Reutilizables Utilizados
- `<x-label>`: Etiquetas de formulario
- `<x-input>`: Campos de texto
- `<x-input-error>`: Mensajes de error
- `<x-button>`: Botón primario de submit

### Información Adicional
Banner azul con:
- Icono de información
- Lista de puntos clave:
  - Estado automático "Pendiente"
  - Notificaciones sobre progreso
  - Beneficio de adjuntar evidencia

## Flujo Completo

```
Usuario autenticado → Dashboard → "Crear Nuevo Ticket" →
Formulario de creación →
  - Llenar título
  - Seleccionar categoría
  - Elegir prioridad (color)
  - Escribir descripción
  - [Opcional] Adjuntar evidencia →
Submit →
  Validación →
  Crear ticket en BD →
  Guardar evidencia (si existe) →
  Flash message →
  Redirect a Dashboard →
  Mostrar mensaje "Ticket creado exitosamente"
```

## Mensajes de Validación Personalizados

```php
'titulo.required' => 'El título es obligatorio.'
'descripcion.required' => 'La descripción es obligatoria.'
'categoria_id.required' => 'Debe seleccionar una categoría.'
'categoria_id.exists' => 'La categoría seleccionada no es válida.'
'prioridad.required' => 'Debe seleccionar una prioridad.'
'prioridad.in' => 'La prioridad seleccionada no es válida.'
'evidencia.image' => 'El archivo debe ser una imagen.'
'evidencia.max' => 'La imagen no debe superar los 5MB.'
```

## Seguridad

### Upload de Archivos
- Validación de tipo (solo imágenes)
- Validación de tamaño (máximo 5MB)
- Nombres aleatorios para prevenir colisiones
- Almacenamiento en directorio protegido

### Asignación Automática
- `usuario_id`: Se asigna automáticamente con `auth()->id()`
- `estado`: Siempre inicia como `PENDIENTE` (0)
- No se permite manipular estos valores desde el formulario

### Protección CSRF
- Todos los formularios incluyen token CSRF automáticamente
- Livewire maneja la protección internamente

## Próximas Funcionalidades

- [ ] Lista de tickets del usuario
- [ ] Vista de detalle de ticket
- [ ] Edición de tickets
- [ ] Sistema de comentarios
- [ ] Vista de administrador para gestionar todos los tickets
- [ ] Cambio de estado por administrador
- [ ] Notificaciones por email
- [ ] Historial de cambios de estado
- [ ] Filtros y búsqueda de tickets

## Archivos Relacionados

### Componentes Livewire
- `app/Livewire/Tickets/CreateTicket.php`

### Vistas
- `resources/views/livewire/tickets/create-ticket.blade.php`
- `resources/views/components/layouts/app.blade.php`
- `resources/views/dashboard.blade.php` (actualizado con botón de acción)

### Modelos
- `app/Models/Ticket.php`
- `app/Models/ArchivoAdjunto.php`
- `app/Models/Categoria.php`

### Rutas
- `routes/web.php` (actualizado con ruta protegida)

### Storage
- `storage/app/public/evidencias/` (directorio de archivos)
- `public/storage/` (enlace simbólico)

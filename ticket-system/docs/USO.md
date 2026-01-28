# Guía de Uso - API de Servicios y Repositorios

## 📋 Índice
1. [TicketService](#ticketservice)
2. [TicketRepository](#ticketrepository)
3. [Actions](#actions)
4. [Query Scopes](#query-scopes)
5. [Form Requests](#form-requests)
6. [Ejemplos Prácticos](#ejemplos-prácticos)

---

## TicketService

**Ubicación**: `app/Services/TicketService.php`

**Responsabilidad**: Coordinar lógica de negocio compleja relacionada con tickets.

### Métodos Disponibles

#### `createTicketWithEvidence()`

Crea un ticket con evidencia adjunta en una transacción.

```php
public function createTicketWithEvidence(
    int $usuarioId,
    string $titulo,
    string $descripcion,
    int $categoriaId,
    string $prioridad,
    ?UploadedFile $evidencia = null
): Ticket
```

**Parámetros**:
- `$usuarioId`: ID del usuario que crea el ticket
- `$titulo`: Título del ticket
- `$descripcion`: Descripción detallada
- `$categoriaId`: ID de la categoría
- `$prioridad`: Valor del enum PrioridadTicket (0, 1, 2)
- `$evidencia`: Archivo opcional (imagen)

**Retorna**: Objeto `Ticket` con relaciones cargadas

**Ejemplo**:

```php
use App\Services\TicketService;
use Illuminate\Http\UploadedFile;

$ticketService = app(TicketService::class);

$ticket = $ticketService->createTicketWithEvidence(
    usuarioId: auth()->id(),
    titulo: 'Error en login',
    descripcion: 'No puedo acceder al sistema',
    categoriaId: 1,
    prioridad: '1', // Media
    evidencia: $request->file('evidencia')
);
```

**Excepciones**:
- `\InvalidArgumentException`: Si el archivo no es una imagen válida

---

#### `attachEvidence()`

Adjunta evidencia a un ticket existente.

```php
public function attachEvidence(Ticket $ticket, UploadedFile $file): ArchivoAdjunto
```

**Parámetros**:
- `$ticket`: Instancia del ticket
- `$file`: Archivo a adjuntar

**Retorna**: Objeto `ArchivoAdjunto` creado

**Ejemplo**:

```php
$archivo = $ticketService->attachEvidence($ticket, $file);
// El archivo se guarda en storage/app/public/evidencias/
```

---

#### `changeEstado()`

Cambia el estado de un ticket y registra el cambio en el historial.

```php
public function changeEstado(
    Ticket $ticket, 
    EstadoTicket $nuevoEstado, 
    ?string $comentario = null
): bool
```

**Parámetros**:
- `$ticket`: Ticket a actualizar
- `$nuevoEstado`: Enum `EstadoTicket`
- `$comentario`: Comentario opcional sobre el cambio

**Retorna**: `true` si fue exitoso

**Ejemplo**:

```php
use App\Enums\EstadoTicket;

$success = $ticketService->changeEstado(
    ticket: $ticket,
    nuevoEstado: EstadoTicket::EnProceso,
    comentario: 'El técnico está revisando el problema'
);

// Esto también crea un registro en historial_estados
```

---

#### `getUserTickets()`

Obtiene todos los tickets de un usuario.

```php
public function getUserTickets(int $userId): Collection
```

**Ejemplo**:

```php
$misTickets = $ticketService->getUserTickets(auth()->id());
```

---

#### `getAllTickets()`

Obtiene todos los tickets del sistema (solo admin).

```php
public function getAllTickets(): Collection
```

---

#### `getStats()`

Obtiene estadísticas de tickets.

```php
public function getStats(?int $userId = null): array
```

**Retorna**:

```php
[
    'total' => 10,
    'pendientes' => 4,
    'en_proceso' => 3,
    'resueltos' => 3,
]
```

**Ejemplo**:

```php
// Estadísticas globales
$stats = $ticketService->getStats();

// Estadísticas de un usuario
$statsUsuario = $ticketService->getStats(auth()->id());
```

---

#### `findTicket()`

Encuentra un ticket por ID con todas sus relaciones.

```php
public function findTicket(int $id): ?Ticket
```

**Relaciones cargadas**:
- categoria
- usuario
- archivosAdjuntos
- comentarios
- historialEstados

---

#### `deleteAttachment()`

Elimina un archivo adjunto del storage y base de datos.

```php
public function deleteAttachment(ArchivoAdjunto $archivo): bool
```

---

## TicketRepository

**Ubicación**: `app/Repositories/TicketRepository.php`

**Responsabilidad**: Abstraer consultas a la base de datos.

### Métodos Disponibles

#### `getByUser()`

```php
public function getByUser(int $userId): Collection
```

Obtiene tickets de un usuario con relaciones básicas, ordenados por fecha.

**Equivalente SQL**:
```sql
SELECT * FROM tickets 
WHERE usuario_id = ?
ORDER BY created_at DESC
```

---

#### `getByEstado()`

```php
public function getByEstado(string $estado, ?int $userId = null): Collection
```

Filtra tickets por estado, opcionalmente por usuario.

**Ejemplo**:

```php
use App\Repositories\Contracts\TicketRepositoryInterface;

$repo = app(TicketRepositoryInterface::class);

// Todos los tickets pendientes
$pendientes = $repo->getByEstado(EstadoTicket::Pendiente->value);

// Tickets pendientes de un usuario
$misPendientes = $repo->getByEstado(EstadoTicket::Pendiente->value, auth()->id());
```

---

#### `getByPrioridad()`

```php
public function getByPrioridad(string $prioridad, ?int $userId = null): Collection
```

Filtra por prioridad.

**Ejemplo**:

```php
use App\Enums\PrioridadTicket;

$urgentes = $repo->getByPrioridad(PrioridadTicket::Alta->value);
```

---

#### `getPendientes()`, `getEnProceso()`, `getResueltos()`

Métodos de conveniencia para estados específicos.

```php
$pendientes = $repo->getPendientes();
$enProceso = $repo->getEnProceso(auth()->id());
$resueltos = $repo->getResueltos();
```

---

## Actions

### CreateTicketAction

**Ubicación**: `app/Actions/Tickets/CreateTicketAction.php`

**Uso en Livewire**:

```php
use App\Actions\Tickets\CreateTicketAction;

public function createTicket(CreateTicketAction $action)
{
    $this->validate();
    
    $ticket = $action->execute(
        data: [
            'usuario_id' => auth()->id(),
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'categoria_id' => $this->categoria_id,
            'prioridad' => $this->prioridad,
        ],
        evidencia: $this->evidencia
    );
}
```

**Uso en API Controller**:

```php
public function store(CreateTicketRequest $request, CreateTicketAction $action)
{
    $ticket = $action->executeFromRequest($request);
    
    return response()->json($ticket, 201);
}
```

---

### ChangeTicketEstadoAction

**Ubicación**: `app/Actions/Tickets/ChangeTicketEstadoAction.php`

**Uso**:

```php
use App\Actions\Tickets\ChangeTicketEstadoAction;
use App\Enums\EstadoTicket;

public function cambiarEstado(Ticket $ticket, ChangeTicketEstadoAction $action)
{
    $success = $action->execute(
        ticket: $ticket,
        nuevoEstado: EstadoTicket::Resuelto,
        comentario: 'Problema solucionado'
    );
    
    // Esto automáticamente dispara el evento TicketUpdated
}
```

---

## Query Scopes

**Ubicación**: `app/Models/Ticket.php`

Los Query Scopes permiten construir queries de forma encadenada y legible.

### Scopes Disponibles

#### Relaciones

```php
// Cargar relaciones completas
Ticket::withFullRelations()->find($id);

// Cargar relaciones básicas (categoria, usuario)
Ticket::withBasicRelations()->get();
```

#### Filtros

```php
// Por usuario
Ticket::forUser(auth()->id())->get();

// Por estado
Ticket::byEstado(EstadoTicket::Pendiente)->get();

// Por prioridad
Ticket::byPrioridad(PrioridadTicket::Alta)->get();

// Por categoría
Ticket::byCategoria(1)->get();
```

#### Estados específicos

```php
Ticket::pendientes()->get();
Ticket::enProceso()->get();
Ticket::resueltos()->get();
```

#### Ordenamiento

```php
Ticket::recent()->get();  // Más recientes primero
Ticket::oldest()->get();  // Más antiguos primero
```

### Encadenamiento

Los scopes se pueden encadenar:

```php
// Tickets pendientes del usuario, con relaciones, ordenados
$tickets = Ticket::forUser(auth()->id())
    ->pendientes()
    ->withBasicRelations()
    ->recent()
    ->get();

// Tickets de alta prioridad en proceso
$urgentesEnProceso = Ticket::byPrioridad(PrioridadTicket::Alta)
    ->enProceso()
    ->withBasicRelations()
    ->get();
```

---

## Form Requests

### CreateTicketRequest

**Ubicación**: `app/Http/Requests/CreateTicketRequest.php`

**Reglas de validación**:

```php
[
    'titulo' => 'required|string|max:255',
    'descripcion' => 'required|string|max:1000',
    'categoria_id' => 'required|exists:categorias,id',
    'prioridad' => 'required|Rule::enum(PrioridadTicket::class)',
    'evidencia' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:2048',
]
```

**Uso en Livewire** (reutilizar validaciones):

```php
public function rules()
{
    return (new CreateTicketRequest())->rules();
}

public function messages()
{
    return (new CreateTicketRequest())->messages();
}
```

---

### UpdateTicketEstadoRequest

**Reglas**:

```php
[
    'estado' => 'required|Rule::enum(EstadoTicket::class)',
    'comentario' => 'nullable|string|max:500',
]
```

**Autorización**: Solo administradores pueden cambiar estados.

---

## Ejemplos Prácticos

### Ejemplo 1: Crear ticket desde un comando CLI

```php
// app/Console/Commands/CreateTicketCommand.php

use App\Services\TicketService;
use App\Enums\PrioridadTicket;

public function handle(TicketService $ticketService)
{
    $ticket = $ticketService->createTicketWithEvidence(
        usuarioId: 1,
        titulo: $this->argument('titulo'),
        descripcion: $this->argument('descripcion'),
        categoriaId: 1,
        prioridad: PrioridadTicket::Media->value,
        evidencia: null
    );
    
    $this->info("Ticket #{$ticket->id} creado exitosamente");
}
```

---

### Ejemplo 2: Dashboard con estadísticas

```php
// En Livewire Component

use App\Services\TicketService;

public function render(TicketService $ticketService)
{
    $stats = $ticketService->getStats();
    $ticketsRecientes = Ticket::withBasicRelations()
        ->recent()
        ->limit(10)
        ->get();
    
    return view('livewire.admin.dashboard', [
        'stats' => $stats,
        'tickets' => $ticketsRecientes,
    ]);
}
```

---

### Ejemplo 3: Filtros avanzados

```php
// Tickets urgentes sin resolver de la última semana

use Carbon\Carbon;

$ticketsUrgentes = Ticket::byPrioridad(PrioridadTicket::Alta)
    ->where('created_at', '>=', Carbon::now()->subWeek())
    ->whereIn('estado', [
        EstadoTicket::Pendiente->value,
        EstadoTicket::EnProceso->value
    ])
    ->withBasicRelations()
    ->recent()
    ->get();
```

---

### Ejemplo 4: Testing con mocks

```php
// tests/Unit/TicketServiceTest.php

use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Services\TicketService;
use Mockery;

public function test_create_ticket_with_evidence()
{
    $mockRepo = Mockery::mock(TicketRepositoryInterface::class);
    $mockRepo->shouldReceive('create')
        ->once()
        ->andReturn(new Ticket(['id' => 1]));
    
    $service = new TicketService($mockRepo);
    
    $ticket = $service->createTicketWithEvidence(
        usuarioId: 1,
        titulo: 'Test',
        descripcion: 'Test description',
        categoriaId: 1,
        prioridad: '0',
        evidencia: null
    );
    
    $this->assertInstanceOf(Ticket::class, $ticket);
}
```

---

## Mejores Prácticas

### ✅ DO

```php
// Usar Service Layer para lógica compleja
$ticketService->createTicketWithEvidence(...);

// Usar Repository para queries
$tickets = $ticketRepository->getByUser($userId);

// Usar Scopes para queries legibles
Ticket::forUser($userId)->pendientes()->recent()->get();

// Usar Actions para operaciones críticas
$action->execute($data, $evidencia);
```

### ❌ DON'T

```php
// NO escribir queries directas en componentes
Ticket::where('usuario_id', $userId)
    ->where('estado', 0)
    ->with(['categoria'])
    ->orderBy('created_at', 'desc')
    ->get();

// NO poner lógica de negocio en componentes Livewire
if ($this->evidencia) {
    $path = $this->evidencia->store('evidencias');
    // ...validaciones MIME...
    ArchivoAdjunto::create([...]);
}

// NO usar strings mágicos
if ($ticket->estado == 0) { ... }  // ❌
if ($ticket->estado === EstadoTicket::Pendiente) { ... }  // ✅
```

---

## Referencia Rápida

| Necesidad | Usar |
|-----------|------|
| Crear ticket | `CreateTicketAction` o `TicketService::createTicketWithEvidence()` |
| Obtener tickets de usuario | `TicketService::getUserTickets()` o `TicketRepository::getByUser()` |
| Cambiar estado | `ChangeTicketEstadoAction` o `TicketService::changeEstado()` |
| Estadísticas | `TicketService::getStats()` |
| Query personalizada | Usar Query Scopes en el modelo |
| Validación | Reutilizar Form Requests |
| Operación crítica | Crear nuevo Action |

---

## Soporte

Para dudas o problemas:
1. Revisa [ARQUITECTURA.md](./ARQUITECTURA.md)
2. Revisa [PATRONES.md](./PATRONES.md)
3. Consulta los tests en `tests/`

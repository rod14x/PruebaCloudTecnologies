# Patrones de Diseño Implementados

## 📋 Índice
1. [Patrones Creacionales](#patrones-creacionales)
2. [Patrones Estructurales](#patrones-estructurales)
3. [Patrones Comportamiento](#patrones-de-comportamiento)
4. [Patrones Arquitecturales](#patrones-arquitecturales)
5. [Ejemplos de Uso](#ejemplos-de-uso)

---

## Patrones Creacionales

### 1. Dependency Injection (DI)

**Propósito**: Invertir el control de dependencias para facilitar testing y desacoplamiento.

**Implementación**:

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->bind(
        TicketRepositoryInterface::class, 
        TicketRepository::class
    );
}
```

**Uso en componentes**:

```php
class TicketService
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository
    ) {}
}
```

**Ventajas**:
- ✅ Facilita testing con mocks
- ✅ Bajo acoplamiento
- ✅ Cambiar implementaciones sin modificar código

---

## Patrones Estructurales

### 1. Repository Pattern

**Propósito**: Abstraer el acceso a datos, separando lógica de persistencia de lógica de negocio.

**Estructura**:

```
Repositories/
├── Contracts/
│   └── TicketRepositoryInterface.php  (Contrato)
└── TicketRepository.php               (Implementación)
```

**Ejemplo**:

```php
// Interface
interface TicketRepositoryInterface
{
    public function getByUser(int $userId): Collection;
    public function getAll(): Collection;
    public function findById(int $id): ?Ticket;
}

// Implementación
class TicketRepository implements TicketRepositoryInterface
{
    public function getByUser(int $userId): Collection
    {
        return Ticket::forUser($userId)
            ->withBasicRelations()
            ->recent()
            ->get();
    }
}
```

**Ventajas**:
- ✅ Abstrae el ORM (puedes cambiar de Eloquent a Query Builder)
- ✅ Centraliza queries en un solo lugar
- ✅ Facilita testing con mocks
- ✅ Reutilización de queries

**Cuándo usar**:
- Queries complejas o repetitivas
- Cuando necesitas cambiar de ORM/DB
- Para facilitar unit testing

---

### 2. Facade Pattern

**Propósito**: Proveer una interfaz simplificada a subsistemas complejos.

**Implementación en Laravel**:

```php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

// En lugar de instanciar StorageManager manualmente
Storage::disk('public')->put($path, $file);

// En lugar de gestionar sesión manualmente
Auth::user();
```

**Ventajas**:
- ✅ API más simple
- ✅ Oculta complejidad interna
- ✅ Punto único de acceso

---

## Patrones de Comportamiento

### 1. Action Pattern (Command Pattern)

**Propósito**: Encapsular una operación como un objeto, siguiendo Single Responsibility Principle.

**Implementación**:

```php
// app/Actions/Tickets/CreateTicketAction.php
class CreateTicketAction
{
    public function __construct(
        private TicketService $ticketService
    ) {}

    public function execute(array $data, ?UploadedFile $evidencia): Ticket
    {
        return $this->ticketService->createTicketWithEvidence(
            usuarioId: $data['usuario_id'],
            titulo: $data['titulo'],
            descripcion: $data['descripcion'],
            categoriaId: $data['categoria_id'],
            prioridad: $data['prioridad'],
            evidencia: $evidencia
        );
    }
}
```

**Uso**:

```php
// En Livewire Component
public function createTicket(CreateTicketAction $action)
{
    $ticket = $action->execute($data, $this->evidencia);
}
```

**Ventajas**:
- ✅ Una acción = Una responsabilidad
- ✅ Fácil de testear
- ✅ Reutilizable en múltiples contextos (API, CLI, Web)
- ✅ Historial de comandos (si implementas logging)

**Cuándo usar**:
- Operaciones críticas (crear ticket, cambiar estado)
- Cuando necesitas logging/auditoría
- Operaciones que disparan eventos

---

### 2. Observer Pattern (Event/Listener)

**Propósito**: Definir dependencia uno-a-muchos entre objetos, notificando automáticamente a observadores.

**Implementación**:

```php
// app/Events/TicketUpdated.php
class TicketUpdated
{
    public function __construct(
        public Ticket $ticket
    ) {}
}

// Disparar evento
event(new TicketUpdated($ticket));

// Escuchar evento en Livewire
class MyTickets extends Component
{
    #[On('ticket-updated')]
    public function refreshTickets()
    {
        // Recargar tickets automáticamente
    }
}
```

**Ventajas**:
- ✅ Desacopla emisor de receptor
- ✅ Múltiples listeners para un evento
- ✅ Fácil agregar comportamiento sin modificar código existente

**Casos de uso**:
- Actualización en tiempo real (Livewire)
- Envío de notificaciones
- Logging de actividades

---

### 3. Strategy Pattern (con Enums)

**Propósito**: Definir familia de algoritmos/comportamientos intercambiables.

**Implementación**:

```php
// app/Enums/EstadoTicket.php
enum EstadoTicket: int
{
    case Pendiente = 0;
    case EnProceso = 1;
    case Resuelto = 2;

    // Estrategia: Cada estado sabe su color
    public function color(): string
    {
        return match($this) {
            self::Pendiente => 'amber',
            self::EnProceso => 'blue',
            self::Resuelto => 'green',
        };
    }

    // Estrategia: Cada estado sabe su label
    public function label(): string
    {
        return match($this) {
            self::Pendiente => 'Pendiente',
            self::EnProceso => 'En Proceso',
            self::Resuelto => 'Resuelto',
        };
    }
}
```

**Uso**:

```php
// En lugar de if/switch dispersos en vistas
$ticket->estado->color();   // 'amber'
$ticket->estado->label();   // 'Pendiente'
```

**Ventajas**:
- ✅ Elimina condicionales dispersos
- ✅ Type-safe (PHP 8.1+)
- ✅ Comportamiento encapsulado
- ✅ Fácil agregar nuevos estados

---

### 4. Query Scope Pattern

**Propósito**: Encapsular queries reutilizables en el modelo.

**Implementación**:

```php
// app/Models/Ticket.php
class Ticket extends Model
{
    // Scope para incluir relaciones
    public function scopeWithBasicRelations(Builder $query): Builder
    {
        return $query->with(['categoria', 'usuario']);
    }

    // Scope para filtrar por usuario
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('usuario_id', $userId);
    }

    // Scope para ordenar
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }
}
```

**Uso encadenado**:

```php
// Antes (queries directas)
Ticket::where('usuario_id', $userId)
    ->with(['categoria', 'usuario'])
    ->orderBy('created_at', 'desc')
    ->get();

// Después (con scopes)
Ticket::forUser($userId)
    ->withBasicRelations()
    ->recent()
    ->get();
```

**Ventajas**:
- ✅ Queries legibles y expresivas
- ✅ Reutilizables en todo el código
- ✅ Composables (chainable)
- ✅ Fácil modificar queries sin buscar todos los usos

---

## Patrones Arquitecturales

### 1. Service Layer Pattern

**Propósito**: Centralizar lógica de negocio compleja, coordinando múltiples recursos.

**Implementación**:

```php
// app/Services/TicketService.php
class TicketService
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository
    ) {}

    public function createTicketWithEvidence(
        int $usuarioId,
        string $titulo,
        string $descripcion,
        int $categoriaId,
        string $prioridad,
        ?UploadedFile $evidencia
    ): Ticket {
        return DB::transaction(function () use (...) {
            // 1. Crear ticket
            $ticket = $this->ticketRepository->create([...]);

            // 2. Si hay evidencia, adjuntarla
            if ($evidencia) {
                $this->attachEvidence($ticket, $evidencia);
            }

            return $ticket->load(['categoria', 'usuario']);
        });
    }
}
```

**Ventajas**:
- ✅ Lógica de negocio centralizada
- ✅ Coordina múltiples repositories
- ✅ Maneja transacciones complejas
- ✅ Reutilizable en API, CLI, Web

**Cuándo usar**:
- Operaciones con múltiples pasos
- Cuando necesitas coordinar varios repositories
- Lógica de negocio que no pertenece al modelo

---

### 2. MVC (Model-View-Controller)

**Propósito**: Separar datos, presentación y lógica de control.

**Implementación en Laravel + Livewire**:

```
Model: app/Models/Ticket.php
View: resources/views/livewire/tickets/create-ticket.blade.php
Controller: app/Livewire/Tickets/CreateTicket.php (híbrido)
```

**Ventajas**:
- ✅ Separación de responsabilidades
- ✅ Múltiples vistas para un modelo
- ✅ Facilita colaboración en equipo

---

## Ejemplos de Uso

### Crear un Ticket (flujo completo)

```php
// 1. Usuario envía formulario
// Livewire Component: app/Livewire/Tickets/CreateTicket.php

public function createTicket(CreateTicketAction $action)
{
    $this->validate();  // Form Request validation
    
    try {
        // 2. Delegar a Action
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
        
        session()->flash('message', 'Ticket creado exitosamente.');
        return redirect()->route('tickets.index');
        
    } catch (\Exception $e) {
        session()->flash('error', 'Error al crear el ticket.');
    }
}
```

```php
// 3. Action coordina el Service
// app/Actions/Tickets/CreateTicketAction.php

public function execute(array $data, ?UploadedFile $evidencia): Ticket
{
    return $this->ticketService->createTicketWithEvidence(
        usuarioId: $data['usuario_id'],
        titulo: $data['titulo'],
        descripcion: $data['descripcion'],
        categoriaId: $data['categoria_id'],
        prioridad: $data['prioridad'],
        evidencia: $evidencia
    );
}
```

```php
// 4. Service coordina Repository y maneja transacción
// app/Services/TicketService.php

public function createTicketWithEvidence(...): Ticket
{
    return DB::transaction(function () use (...) {
        // Usar Repository
        $ticket = $this->ticketRepository->create([...]);
        
        // Adjuntar evidencia si existe
        if ($evidencia) {
            $this->attachEvidence($ticket, $evidencia);
        }
        
        return $ticket;
    });
}
```

```php
// 5. Repository usa Query Scopes del Model
// app/Repositories/TicketRepository.php

public function create(array $data): Ticket
{
    return Ticket::create($data);
}
```

---

## Comparación: Antes vs Después

### ❌ Antes (sin patrones)

```php
// Todo en el componente Livewire
public function createTicket()
{
    // Validación inline
    $this->validate([...]);
    
    // Query directo
    $ticket = Ticket::create([...]);
    
    // Lógica de archivo dispersa
    if ($this->evidencia) {
        $path = $this->evidencia->store('evidencias');
        ArchivoAdjunto::create([...]);
    }
}
```

**Problemas**:
- ❌ No reutilizable
- ❌ Difícil de testear
- ❌ Lógica dispersa
- ❌ Violación de SRP

### ✅ Después (con patrones)

```php
public function createTicket(CreateTicketAction $action)
{
    $this->validate();
    $ticket = $action->execute($data, $this->evidencia);
}
```

**Ventajas**:
- ✅ Reutilizable (Action puede usarse en API, CLI)
- ✅ Testeable (mock del Action)
- ✅ Lógica centralizada (Service)
- ✅ Cumple SRP

---

## Resumen de Patrones

| Patrón | Categoría | Ubicación | Propósito |
|--------|-----------|-----------|-----------|
| **Dependency Injection** | Creacional | AppServiceProvider | Inyectar dependencias |
| **Repository** | Estructural | `app/Repositories/` | Abstraer acceso a datos |
| **Facade** | Estructural | Laravel facades | Simplificar APIs complejas |
| **Action/Command** | Comportamiento | `app/Actions/` | Encapsular operaciones |
| **Observer/Event** | Comportamiento | `app/Events/` | Notificar cambios |
| **Strategy (Enum)** | Comportamiento | `app/Enums/` | Comportamientos intercambiables |
| **Query Scope** | Comportamiento | Models | Queries reutilizables |
| **Service Layer** | Arquitectural | `app/Services/` | Lógica de negocio |
| **MVC** | Arquitectural | Laravel | Separar responsabilidades |

---

## Recomendaciones

1. **No sobre-ingenierizar**: Si una operación es simple, no necesitas todos los patrones
2. **Consistencia**: Si usas un patrón, úsalo en todo el proyecto
3. **Testing**: Escribe tests para validar que los patrones funcionan
4. **Documentación**: Mantén esta documentación actualizada
5. **Code Review**: Asegura que el equipo siga los patrones establecidos

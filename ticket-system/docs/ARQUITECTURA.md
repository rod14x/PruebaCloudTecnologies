# Arquitectura del Sistema de Gestión de Tickets

## 📋 Índice
1. [Visión General](#visión-general)
2. [Capas de la Arquitectura](#capas-de-la-arquitectura)
3. [Flujo de Datos](#flujo-de-datos)
4. [Estructura de Directorios](#estructura-de-directorios)
5. [Tecnologías Utilizadas](#tecnologías-utilizadas)

---

## Visión General

El sistema implementa una **arquitectura en capas** siguiendo principios **SOLID** y patrones de diseño profesionales para garantizar:

- ✅ **Mantenibilidad**: Código organizado y fácil de modificar
- ✅ **Escalabilidad**: Preparado para crecer sin refactorización masiva
- ✅ **Testabilidad**: Cada capa puede testearse independientemente
- ✅ **Desacoplamiento**: Componentes independientes y reutilizables

---

## Capas de la Arquitectura

```
┌─────────────────────────────────────────────────┐
│         PRESENTACIÓN (Livewire Components)      │
│  - CreateTicket.php                             │
│  - MyTickets.php                                │
│  - AdminTickets.php (por implementar)           │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│         ACTIONS (Operaciones Críticas)          │
│  - CreateTicketAction                           │
│  - ChangeTicketEstadoAction                     │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│         SERVICES (Lógica de Negocio)            │
│  - TicketService                                │
│    * Coordina repositories y actions            │
│    * Maneja transacciones complejas             │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│         REPOSITORIES (Acceso a Datos)           │
│  - TicketRepository (implementación)            │
│  - TicketRepositoryInterface (contrato)         │
│    * Abstrae queries                            │
│    * Usa Query Scopes del modelo                │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│         MODELS (Eloquent ORM)                   │
│  - Ticket                                       │
│  - User                                         │
│  - Categoria                                    │
│  - ArchivoAdjunto                               │
│    * Query Scopes reutilizables                 │
│    * Relaciones Eloquent                        │
└─────────────────────────────────────────────────┘
```

### Descripción de Capas

#### 1. **Presentación (Livewire Components)**
- **Responsabilidad**: Interacción con el usuario
- **Características**:
  - Recibe input del usuario
  - Valida datos básicos
  - Delega operaciones a Actions
  - Renderiza vistas

#### 2. **Actions (Comandos/Operaciones)**
- **Responsabilidad**: Encapsular operaciones críticas
- **Características**:
  - Una acción = Una responsabilidad (SRP)
  - Coordinan servicios
  - Disparan eventos
  - Manejan casos de uso específicos

#### 3. **Services (Capa de Negocio)**
- **Responsabilidad**: Lógica de negocio compleja
- **Características**:
  - Coordina múltiples repositories
  - Maneja transacciones
  - Aplica reglas de negocio
  - Puede usar otros servicios

#### 4. **Repositories (Persistencia)**
- **Responsabilidad**: Acceso y manipulación de datos
- **Características**:
  - Abstrae el ORM (Eloquent)
  - Define interface (contrato)
  - Usa Query Scopes del modelo
  - Facilita testing con mocks

#### 5. **Models (Datos)**
- **Responsabilidad**: Representar entidades y relaciones
- **Características**:
  - Eloquent ORM
  - Query Scopes reutilizables
  - Casts y mutators
  - Relaciones entre modelos

---

## Flujo de Datos

### Ejemplo: Crear un Ticket

```
Usuario → CreateTicket Component → Validación → CreateTicketAction
    ↓
CreateTicketAction → TicketService.createTicketWithEvidence()
    ↓
TicketService → TicketRepository.create()
    ↓
TicketRepository → Ticket::create()
    ↓
Base de Datos (PostgreSQL)
    ↓
Respuesta → Service → Action → Component → Usuario
```

### Ejemplo: Cambiar Estado de Ticket (Admin)

```
Admin → AdminTickets Component → ChangeTicketEstadoAction
    ↓
Action → TicketService.changeEstado()
    ↓
Service → TicketRepository.update() + Registrar Historial
    ↓
Action → Dispara Evento TicketUpdated
    ↓
Evento → MyTickets Component escucha → Refresh automático
```

---

## Estructura de Directorios

```
app/
├── Actions/              # Operaciones críticas
│   └── Tickets/
│       ├── CreateTicketAction.php
│       └── ChangeTicketEstadoAction.php
│
├── Services/             # Lógica de negocio
│   └── TicketService.php
│
├── Repositories/         # Acceso a datos
│   ├── Contracts/
│   │   └── TicketRepositoryInterface.php
│   └── TicketRepository.php
│
├── Models/               # Entidades Eloquent
│   ├── Ticket.php (con Query Scopes)
│   ├── User.php
│   ├── Categoria.php
│   └── ArchivoAdjunto.php
│
├── Enums/                # Tipos enumerados
│   ├── RolUsuario.php
│   ├── EstadoTicket.php
│   └── PrioridadTicket.php
│
├── Http/
│   ├── Requests/         # Validaciones reutilizables
│   │   ├── CreateTicketRequest.php
│   │   └── UpdateTicketEstadoRequest.php
│   └── Livewire/         # Componentes de presentación
│       └── Tickets/
│           ├── CreateTicket.php
│           └── MyTickets.php
│
├── Events/               # Eventos del sistema
│   └── TicketUpdated.php
│
└── Providers/            # Service Providers
    └── AppServiceProvider.php (DI bindings)
```

---

## Tecnologías Utilizadas

### Backend
- **Laravel 11**: Framework PHP moderno
- **PHP 8.4**: Últimas características (Enums, Named Arguments, etc.)
- **Livewire 3**: Framework full-stack reactivo
- **PostgreSQL 18.1**: Base de datos relacional

### Frontend
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: JavaScript framework ligero (incluido en Livewire)
- **Vite**: Build tool moderno

### Patrones Implementados
- **MVC**: Model-View-Controller (base)
- **Repository Pattern**: Abstracción de datos
- **Service Layer**: Lógica de negocio
- **Action Pattern**: Operaciones encapsuladas
- **Observer Pattern**: Sistema de eventos
- **Strategy Pattern**: Enums con comportamiento
- **Dependency Injection**: Inversión de control
- **Query Scopes**: Queries reutilizables

---

## Principios SOLID Aplicados

### S - Single Responsibility Principle
- Cada Action tiene una sola responsabilidad
- Repositories solo manejan persistencia
- Services solo contienen lógica de negocio

### O - Open/Closed Principle
- Interfaces permiten extensión sin modificación
- Query Scopes permiten composición de queries

### L - Liskov Substitution Principle
- TicketRepositoryInterface puede ser reemplazada por cualquier implementación

### I - Interface Segregation Principle
- Interfaces específicas y no sobrecargadas

### D - Dependency Inversion Principle
- Dependencias inyectadas vía constructor
- Dependemos de abstracciones (interfaces)

---

## Ventajas de esta Arquitectura

1. **Testing**: Cada capa puede testearse en aislamiento
2. **Mantenimiento**: Cambios localizados, no dispersos
3. **Escalabilidad**: Fácil agregar features sin romper código existente
4. **Onboarding**: Nuevos desarrolladores entienden rápidamente
5. **Flexibilidad**: Cambiar ORM/DB sin afectar lógica de negocio
6. **Reusabilidad**: Componentes reutilizables (Actions, Services, Scopes)

---

## Próximos Pasos

- [ ] Implementar caching en Repository layer
- [ ] Agregar logging estructurado
- [ ] Implementar rate limiting
- [ ] Agregar sistema de notificaciones
- [ ] Implementar API REST con misma arquitectura

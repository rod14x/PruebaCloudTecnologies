# Sistema de Gestión de Tickets - Documentación

## 📚 Índice de Documentación

### Documentos Principales

1. **[ARQUITECTURA.md](./ARQUITECTURA.md)** 🏗️
   - Visión general de la arquitectura
   - Capas del sistema
   - Flujo de datos
   - Estructura de directorios
   - Principios SOLID aplicados

2. **[PATRONES.md](./PATRONES.md)** 🎨
   - Patrones de diseño implementados
   - Patrones creacionales (DI)
   - Patrones estructurales (Repository, Facade)
   - Patrones de comportamiento (Action, Observer, Strategy, Query Scopes)
   - Patrones arquitecturales (Service Layer, MVC)
   - Ejemplos de uso y comparaciones

3. **[USO.md](./USO.md)** 📖
   - Guía de uso de APIs
   - TicketService
   - TicketRepository
   - Actions
   - Query Scopes
   - Form Requests
   - Ejemplos prácticos
   - Mejores prácticas

---

## 🚀 Inicio Rápido

### Para Desarrolladores Nuevos

1. Lee **ARQUITECTURA.md** para entender la estructura general
2. Revisa **PATRONES.md** para conocer los patrones aplicados
3. Consulta **USO.md** cuando necesites usar los servicios

### Para Desarrolladores Experimentados

- Ve directo a **USO.md** para la referencia de API
- Consulta **PATRONES.md** si necesitas entender una implementación específica

---

## 📊 Diagramas

### Flujo de Creación de Ticket

```
Usuario → CreateTicket Component
    ↓ (validación)
CreateTicketAction
    ↓
TicketService.createTicketWithEvidence()
    ↓ (transacción DB)
TicketRepository.create()
    ↓
Ticket::create() (Eloquent)
    ↓
PostgreSQL
```

### Arquitectura en Capas

```
┌─────────────────────┐
│    Presentación     │  Livewire Components
├─────────────────────┤
│      Actions        │  Operaciones críticas
├─────────────────────┤
│      Services       │  Lógica de negocio
├─────────────────────┤
│    Repositories     │  Acceso a datos
├─────────────────────┤
│       Models        │  Eloquent ORM
└─────────────────────┘
```

---

## 🎯 Características Principales

### Patrones Implementados

- ✅ **Repository Pattern**: Abstracción de datos
- ✅ **Service Layer**: Lógica de negocio centralizada
- ✅ **Action Pattern**: Operaciones encapsuladas
- ✅ **Observer Pattern**: Sistema de eventos
- ✅ **Strategy Pattern**: Enums con comportamiento
- ✅ **Query Scopes**: Queries reutilizables
- ✅ **Dependency Injection**: Bajo acoplamiento

### Beneficios

- 🧪 **Testeable**: Cada capa se puede testear independientemente
- 🔧 **Mantenible**: Cambios localizados, código organizado
- 📈 **Escalable**: Fácil agregar features sin romper código
- 🔌 **Desacoplado**: Componentes independientes
- 📚 **Documentado**: Documentación completa y actualizada

---

## 🛠️ Stack Tecnológico

- **Backend**: Laravel 11, PHP 8.4
- **Frontend**: Livewire 3, Tailwind CSS, Alpine.js
- **Base de Datos**: PostgreSQL 18.1
- **Build Tool**: Vite 7.3.1

---

## 📝 Convenciones de Código

### Nomenclatura

- **Clases**: PascalCase (`TicketService`, `CreateTicketAction`)
- **Métodos**: camelCase (`getUserTickets`, `createTicket`)
- **Variables**: camelCase (`$userId`, `$ticketService`)
- **Constantes**: SCREAMING_SNAKE_CASE (en Enums)

### Estructura de Archivos

```
app/
├── Actions/Tickets/          # Una acción por archivo
├── Services/                 # Un servicio por dominio
├── Repositories/
│   ├── Contracts/           # Interfaces
│   └── [Implementation].php # Implementaciones
├── Models/                  # Un modelo por tabla
├── Enums/                   # Enums con comportamiento
└── Http/
    ├── Requests/            # Validaciones reutilizables
    └── Livewire/            # Componentes de presentación
```

---

## 🧪 Testing

### Estructura de Tests

```
tests/
├── Unit/
│   ├── Services/
│   │   └── TicketServiceTest.php
│   └── Repositories/
│       └── TicketRepositoryTest.php
├── Feature/
│   └── Tickets/
│       ├── CreateTicketTest.php
│       └── ChangeEstadoTest.php
└── TestCase.php
```

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter=TicketServiceTest

# Con cobertura
php artisan test --coverage
```

---

## 🔍 Resolución de Problemas

### Error: "Target interface not bound"

**Solución**: Verifica que la interface esté registrada en `AppServiceProvider`

```php
$this->app->bind(
    TicketRepositoryInterface::class, 
    TicketRepository::class
);
```

### Error: "Call to undefined method scopeXxx"

**Solución**: Los Query Scopes deben definirse en el modelo con prefijo `scope`

```php
// Correcto
public function scopeForUser(Builder $query, int $userId): Builder

// Incorrecto
public function forUser(Builder $query, int $userId): Builder
```

---

## 📖 Referencias Adicionales

### Laravel
- [Documentación oficial de Laravel](https://laravel.com/docs)
- [Laravel Eloquent](https://laravel.com/docs/eloquent)
- [Query Scopes](https://laravel.com/docs/eloquent#query-scopes)

### Patrones de Diseño
- [Refactoring Guru - Design Patterns](https://refactoring.guru/design-patterns)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)

### Livewire
- [Documentación de Livewire](https://livewire.laravel.com)
- [Livewire Events](https://livewire.laravel.com/docs/events)

---

## 🤝 Contribución

Para mantener la calidad del código:

1. **Lee la documentación** antes de hacer cambios
2. **Sigue los patrones** establecidos
3. **Escribe tests** para nuevas features
4. **Actualiza la documentación** si es necesario
5. **Haz code review** antes de mergear

---

## 📧 Contacto

Para dudas sobre la arquitectura o patrones implementados, consulta primero:
1. Esta documentación
2. El código fuente (está comentado)
3. Los tests (ejemplos de uso)

---

## 🗓️ Histórico de Cambios

### v1.0.0 - Refactorización Inicial (2026-01-27)
- ✅ Implementación de Repository Pattern
- ✅ Implementación de Service Layer
- ✅ Implementación de Action Pattern
- ✅ Implementación de Query Scopes
- ✅ Implementación de Form Requests
- ✅ Documentación completa
- ✅ Aplicación de principios SOLID

### v0.1.0 - Implementación Base
- ✅ Sistema de autenticación
- ✅ Creación de tickets
- ✅ Visualización de tickets
- ✅ Upload de evidencias
- ✅ Estados y prioridades con Enums

---

## 📋 Checklist de Implementación

Para nuevas features, asegúrate de:

- [ ] Crear Repository (si es necesario)
- [ ] Crear Service (para lógica compleja)
- [ ] Crear Action (para operaciones críticas)
- [ ] Crear Form Request (para validaciones)
- [ ] Agregar Query Scopes (si aplica)
- [ ] Escribir tests
- [ ] Actualizar documentación
- [ ] Code review

---

*Última actualización: 27 de enero de 2026*

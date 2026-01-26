# Enums y Type Safety

## Descripcion

El proyecto implementa PHP Enums (PHP 8.1+) para garantizar type-safety y mejorar la seguridad en el manejo de valores constantes. Esta aproximacion reemplaza las constantes publicas tradicionales con enumeraciones inmutables y fuertemente tipadas.

## Enums Implementados

### 1. RolUsuario

Ubicacion: `app/Enums/RolUsuario.php`

Define los roles de usuario en el sistema.

```php
enum RolUsuario: int
{
    case EMPLEADO = 0;
    case ADMINISTRADOR = 1;
}
```

**Metodos:**
- `nombre()`: Retorna el nombre legible del rol
- `esAdministrador()`: Verifica si es rol administrador
- `esEmpleado()`: Verifica si es rol empleado

**Uso:**
```php
$user->rol = RolUsuario::ADMINISTRADOR;
if ($user->rol === RolUsuario::ADMINISTRADOR) {
    // Logica para admin
}
```

---

### 2. PrioridadTicket

Ubicacion: `app/Enums/PrioridadTicket.php`

Define los niveles de prioridad de los tickets.

```php
enum PrioridadTicket: int
{
    case BAJA = 0;
    case MEDIA = 1;
    case ALTA = 2;
}
```

**Metodos:**
- `nombre()`: Retorna 'Baja', 'Media' o 'Alta'
- `color()`: Retorna codigo de color ('green', 'yellow', 'red')

**Uso:**
```php
$ticket->prioridad = PrioridadTicket::ALTA;
$colorBadge = $ticket->prioridad->color(); // 'red'
```

---

### 3. EstadoTicket

Ubicacion: `app/Enums/EstadoTicket.php`

Define los estados posibles de un ticket.

```php
enum EstadoTicket: int
{
    case PENDIENTE = 0;
    case EN_PROCESO = 1;
    case RESUELTO = 2;
}
```

**Metodos:**
- `nombre()`: Retorna 'Pendiente', 'En Proceso' o 'Resuelto'
- `color()`: Retorna codigo de color ('gray', 'blue', 'green')

**Uso:**
```php
$ticket->estado = EstadoTicket::EN_PROCESO;
$nombreEstado = $ticket->estado->nombre(); // 'En Proceso'
```

---

## Ventajas de Usar Enums

### 1. Type Safety
Los enums garantizan que solo se asignen valores validos:

```php
// ERROR: No compila
$user->rol = 5; 

// CORRECTO: Type-safe
$user->rol = RolUsuario::ADMINISTRADOR;
```

### 2. Inmutabilidad
Los valores de los enums no pueden ser modificados en runtime, previniendo manipulacion maliciosa.

### 3. Autocompletado IDE
Los IDEs modernos ofrecen autocompletado y validacion en tiempo de desarrollo.

### 4. Refactorizacion Segura
Cambiar un enum actualiza automaticamente todas sus referencias, reduciendo errores.

### 5. Documentacion Implicita
Los enums son autodocumentados - el nombre del case explica su proposito.

---

## Integracion con Eloquent

### Casting Automatico

Laravel convierte automaticamente entre valores de base de datos y enums:

```php
protected function casts(): array
{
    return [
        'rol' => RolUsuario::class,
        'prioridad' => PrioridadTicket::class,
        'estado' => EstadoTicket::class,
    ];
}
```

### Funcionamiento

1. **Al leer de BD**: `0` (int) -> `RolUsuario::EMPLEADO` (enum)
2. **Al guardar en BD**: `RolUsuario::EMPLEADO` (enum) -> `0` (int)

---

## Uso en Middleware

El middleware CheckRole utiliza enums para validacion type-safe:

```php
public function handle(Request $request, Closure $next, string $role): Response
{
    $requiredRole = RolUsuario::from((int) $role);
    
    if ($request->user()->rol !== $requiredRole) {
        abort(403);
    }
    
    return $next($request);
}
```

**En rutas:**
```php
Route::middleware(['auth', 'role:1'])->group(...); // Admin
Route::middleware(['auth', 'role:0'])->group(...); // Empleado
```

---

## Comparacion: Antes vs Despues

### Antes (Constantes)

```php
class User extends Model
{
    const ROL_EMPLEADO = 0;
    const ROL_ADMINISTRADOR = 1;
    
    public function esAdministrador()
    {
        return $this->rol === self::ROL_ADMINISTRADOR;
    }
}

// Uso
$user->rol = 1; // Magic number, poco claro
$user->rol = 999; // Valor invalido, pero permitido
```

**Problemas:**
- Valores magicos poco claros
- Sin validacion de valores invalidos
- Facil de manipular
- Sin autocompletado

### Despues (Enums)

```php
class User extends Model
{
    protected function casts(): array
    {
        return ['rol' => RolUsuario::class];
    }
    
    public function esAdministrador()
    {
        return $this->rol === RolUsuario::ADMINISTRADOR;
    }
}

// Uso
$user->rol = RolUsuario::ADMINISTRADOR; // Claro y explicito
$user->rol = 999; // ERROR: Type error
```

**Ventajas:**
- Codigo autodocumentado
- Validacion automatica
- Inmutable y seguro
- Autocompletado IDE completo

---

## Migraciones y Compatibilidad

Las migraciones permanecen sin cambios - siguen usando TINYINT:

```php
$table->tinyInteger('rol')->default(0);
$table->tinyInteger('prioridad');
$table->tinyInteger('estado')->default(0);
```

Los enums son una capa de abstraccion sobre los valores enteros en la base de datos.

---

## Testing con Enums

```php
// En tests
$user = User::factory()->create([
    'rol' => RolUsuario::ADMINISTRADOR
]);

$this->assertTrue($user->esAdministrador());
$this->assertEquals('Administrador', $user->rol->nombre());
```

---

## Mejores Practicas

1. **Siempre usar enums para valores constantes**
   - Roles, estados, prioridades, categorias fijas

2. **Agregar metodos helpers en los enums**
   - `nombre()`, `color()`, `icono()`, etc.

3. **No exponer valores directos**
   ```php
   // MAL
   return $user->rol->value;
   
   // BIEN
   return $user->rol->nombre();
   ```

4. **Documentar cada caso**
   ```php
   enum RolUsuario: int
   {
       case EMPLEADO = 0;      // Puede crear y ver tickets
       case ADMINISTRADOR = 1; // Puede editar y eliminar
   }
   ```

---

## Migracion desde Constantes

Si necesitas migrar codigo existente:

1. Crear el enum correspondiente
2. Agregar cast en el modelo
3. Reemplazar referencias: `Model::CONSTANTE` -> `Enum::CASE`
4. Actualizar seeders y factories
5. Ejecutar tests para validar

---

## Recursos

- [PHP Enums Documentation](https://www.php.net/manual/en/language.enumerations.php)
- [Laravel Enum Casting](https://laravel.com/docs/11.x/eloquent-mutators#enum-casting)

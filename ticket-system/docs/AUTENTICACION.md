# Sistema de Autenticación

## Descripción General

Sistema de autenticación personalizado implementado con Livewire 3.x que incluye registro de usuarios, inicio de sesión, recordar sesión y recuperación de contraseña mediante códigos de 6 dígitos.

## Características Implementadas

### 1. Registro de Usuarios
- **Componente**: `App\Livewire\Auth\Register`
- **Ruta**: `/register`
- **Campos requeridos**:
  - Nombre completo
  - Email (único)
  - DNI (único, máximo 20 caracteres)
  - Teléfono (máximo 20 caracteres)
  - Contraseña (mínimo 8 caracteres con confirmación)
- **Comportamiento**:
  - Asigna automáticamente rol `EMPLEADO` (0)
  - Inicia sesión automáticamente después del registro
  - Redirecciona al dashboard

### 2. Inicio de Sesión
- **Componente**: `App\Livewire\Auth\Login`
- **Ruta**: `/login`
- **Campos**:
  - Email
  - Contraseña
  - Recordar sesión (checkbox)
- **Características**:
  - Validación de credenciales
  - Opción "Recordar sesión" mantiene sesión activa
  - Mensajes de error en tiempo real
  - Redirección al dashboard tras login exitoso

### 3. Recuperación de Contraseña

#### Paso 1: Solicitar Código
- **Componente**: `App\Livewire\Auth\ForgotPassword`
- **Ruta**: `/forgot-password`
- **Funcionamiento**:
  - Usuario ingresa su email
  - Sistema genera código aleatorio de 6 dígitos (000000-999999)
  - Código se hashea con `Hash::make()` antes de guardar
  - Código expira en 30 minutos
  - Se muestra el código en pantalla (en producción se enviará por email)
  - Email se guarda en sesión para el siguiente paso

#### Paso 2: Restablecer Contraseña
- **Componente**: `App\Livewire\Auth\ResetPassword`
- **Ruta**: `/reset-password`
- **Validaciones**:
  - Código debe existir en la base de datos
  - Código no debe estar expirado
  - Nueva contraseña mínimo 8 caracteres con confirmación
- **Comportamiento**:
  - Valida código con `Hash::check()`
  - Actualiza contraseña del usuario
  - Limpia campos de recuperación (`codigo_recuperacion` y `codigo_recuperacion_expira`)
  - Inicia sesión automáticamente
  - Redirecciona al dashboard

### 4. Cierre de Sesión
- **Ruta**: POST `/logout`
- **Comportamiento**: Invalida sesión y redirecciona a login

## Campos Adicionales en Tabla Users

```php
$table->string('dni', 20)->unique();
$table->string('telefono', 20);
$table->string('codigo_recuperacion')->nullable();
$table->timestamp('codigo_recuperacion_expira')->nullable();
```

## Modelo User - Configuración

### Fillable
```php
protected $fillable = [
    'name',
    'email',
    'dni',
    'telefono',
    'password',
    'rol',
    'codigo_recuperacion',
    'codigo_recuperacion_expira',
];
```

### Hidden
```php
protected $hidden = [
    'password',
    'remember_token',
    'codigo_recuperacion',
];
```

### Casts
```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'rol' => RolUsuario::class,
        'codigo_recuperacion_expira' => 'datetime',
    ];
}
```

## Componentes Reutilizables

### Layouts
- **`components.layouts.guest`**: Layout para páginas de autenticación
  - Diseño minimalista moderno
  - Gradiente de fondo (slate-50 a slate-100)
  - Tarjeta centrada con sombra
  - Logo con icono SVG de documento
  - Colores de marca: azul e índigo

### Formularios
- **`x-input`**: Campo de texto con estilos consistentes
  - Borde redondeado
  - Focus ring azul
  - Estados disabled
  - Merge de atributos

- **`x-label`**: Etiquetas para formularios
  - Texto slate-700
  - Peso medio
  - Tamaño pequeño

- **`x-button`**: Botón con variantes
  - Variante primaria (azul)
  - Variante secundaria (blanco con borde)
  - Variante link (texto simple)
  - Estados hover y disabled

- **`x-input-error`**: Mensajes de error
  - Texto rojo pequeño
  - Directiva `@error`

## Flujo de Autenticación

### Registro
```
Usuario → /register → Formulario → Validación → 
Crear User (rol: EMPLEADO) → Auth::login() → /dashboard
```

### Login
```
Usuario → /login → Credenciales → 
Auth::attempt(remember: true/false) → /dashboard
```

### Recuperación de Contraseña
```
Usuario → /forgot-password → Email → 
Generar código 6 dígitos → Hash y guardar → 
Mostrar código → Session flash →

Usuario → /reset-password → Validar código y expiración → 
Actualizar password → Limpiar campos recuperación → 
Auth::login() → /dashboard
```

## Seguridad Implementada

1. **Contraseñas hasheadas**: Uso de `Hash::make()` y cast `hashed`
2. **Códigos de recuperación hasheados**: No se guardan en texto plano
3. **Expiración de códigos**: 30 minutos de validez
4. **Validación de unicidad**: DNI y email únicos
5. **CSRF Protection**: Tokens en todos los formularios
6. **Campos ocultos**: `codigo_recuperacion` en array `$hidden`
7. **Mass Assignment Protection**: Solo campos en `$fillable` son asignables

## Usuarios de Prueba

### Administrador
- **Email**: admin@test.com
- **Password**: password
- **DNI**: 12345678
- **Teléfono**: 987654321
- **Rol**: ADMINISTRADOR (1)

### Empleado
- **Email**: empleado@test.com
- **Password**: password
- **DNI**: 87654321
- **Teléfono**: 912345678
- **Rol**: EMPLEADO (0)

## Próximas Mejoras

- [ ] Integrar envío de email para códigos de recuperación
- [ ] Agregar verificación de email
- [ ] Implementar rate limiting en login
- [ ] Agregar autenticación de dos factores (2FA)
- [ ] Logs de actividad de autenticación
- [ ] Recuperación por SMS como alternativa

## Archivos Relacionados

### Componentes Livewire
- `app/Livewire/Auth/Login.php`
- `app/Livewire/Auth/Register.php`
- `app/Livewire/Auth/ForgotPassword.php`
- `app/Livewire/Auth/ResetPassword.php`

### Vistas
- `resources/views/livewire/auth/login.blade.php`
- `resources/views/livewire/auth/register.blade.php`
- `resources/views/livewire/auth/forgot-password.blade.php`
- `resources/views/livewire/auth/reset-password.blade.php`
- `resources/views/components/layouts/guest.blade.php`
- `resources/views/dashboard.blade.php`

### Componentes Blade
- `resources/views/components/input.blade.php`
- `resources/views/components/label.blade.php`
- `resources/views/components/button.blade.php`
- `resources/views/components/input-error.blade.php`

### Migraciones
- `database/migrations/2026_01_26_205137_agregar_campos_adicionales_a_users_table.php`

### Modelos y Configuración
- `app/Models/User.php`
- `database/seeders/UserSeeder.php`
- `routes/web.php`

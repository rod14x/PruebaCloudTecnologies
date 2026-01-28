# Guía de Instalación - Sistema de Gestión de Incidentes Cloud Technologies

## Requisitos Previos

Asegúrate de tener instalado en tu sistema:

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x y **npm** >= 9.x
- **PostgreSQL** >= 13 (o MySQL/SQLite si prefieres)
- **Git**

### Verificar versiones instaladas

```bash
php -v
composer -V
node -v
npm -v
psql --version
```

---

## Instalación Paso a Paso

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/PruebaCloudTecnologies.git
cd PruebaCloudTecnologies/ticket-system
```

### 2. Instalar Dependencias de PHP

```bash
composer install
```

Este comando instalará todas las dependencias de Laravel definidas en `composer.json`, incluyendo:
- Laravel Framework
- Livewire
- Laravel Reverb
- Etc.

### 3. Instalar Dependencias de Node.js

```bash
npm install
```

Esto instalará:
- Vite
- Tailwind CSS
- Alpine.js
- Chart.js
- Concurrently (para ejecutar múltiples comandos)

### 4. Configurar Variables de Entorno

Copia el archivo de ejemplo `.env.example` y renómbralo a `.env`:

```bash
cp .env.example .env
```

Edita el archivo `.env` con tu configuración:

```env
APP_NAME="Cloud Technologies Tickets"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de datos PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ticket_system
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# Laravel Reverb (WebSockets)
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

# Vite
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### 5. Generar Clave de Aplicación

```bash
php artisan key:generate
```

Este comando generará automáticamente la `APP_KEY` en tu archivo `.env`.

### 6. Crear la Base de Datos

#### Para PostgreSQL:

```bash
# Acceder a PostgreSQL
psql -U postgres

# Crear la base de datos
CREATE DATABASE ticket_system;

# Salir de PostgreSQL
\q
```

#### Para MySQL:

```bash
# Acceder a MySQL
mysql -u root -p

# Crear la base de datos
CREATE DATABASE ticket_system;

# Salir de MySQL
exit;
```

### 7. Ejecutar Migraciones

```bash
php artisan migrate
```

Esto creará todas las tablas necesarias:
- users
- tickets
- categorias
- comentarios
- archivos
- historial_estados
- etc.

### 8. Poblar la Base de Datos (Seeders)

```bash
php artisan db:seed
```

Esto creará datos de prueba:
- **Usuarios:**
  - Admin: `admin@cloudtech.com` / `password`
  - Empleado: `empleado@cloudtech.com` / `password`
- **Categorías** (Hardware, Software, Red, Otro)
- **Tickets de ejemplo**

### 9. Instalar Laravel Reverb (WebSockets)

```bash
php artisan reverb:install
```

Sigue las instrucciones del instalador. Esto configurará Laravel Reverb para notificaciones en tiempo real.

### 10. Configurar Storage Link

```bash
php artisan storage:link
```

Esto creará un enlace simbólico de `storage/app/public` a `public/storage` para acceder a archivos subidos.

---

## Ejecutar el Proyecto

### Opción 1: Usar el Script Automático (Recomendado)

El proyecto incluye scripts en `package.json` que usan **concurrently** para ejecutar todos los servicios necesarios:

```bash
npm run serve
```

Este comando ejecutará simultáneamente:
- **Vite** (servidor de desarrollo frontend) en `http://localhost:5173`
- **Laravel Artisan Serve** (servidor backend) en `http://localhost:8000`
- **Laravel Reverb** (servidor WebSocket) en `ws://localhost:8080`

**Accede a la aplicación en:** [http://localhost:8000](http://localhost:8000)

## Credenciales de Acceso

### Administrador
- **Email:** `admin@cloudtech.com`
- **Contraseña:** `password`
- **Rol:** Administrador (puede gestionar todos los tickets)

### Empleado
- **Email:** `empleado@cloudtech.com`
- **Contraseña:** `password`
- **Rol:** Empleado (puede crear y ver sus propios tickets)

---

## Scripts Disponibles

En `package.json` están configurados los siguientes scripts:

```bash
# Desarrollo (ejecuta todo)
npm run dev

# Solo Vite
npm run vite

# Producción
npm run build

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## Estructura del Proyecto

```
ticket-system/
├── app/
│   ├── Models/           # Modelos Eloquent
│   ├── Http/
│   │   ├── Livewire/    # Componentes Livewire
│   │   └── Middleware/  # Middlewares personalizados
│   ├── Repositories/    # Capa de repositorios
│   ├── Services/        # Lógica de negocio
│   └── Enums/          # Enums (Estado, Prioridad, Rol)
├── database/
│   ├── migrations/      # Migraciones
│   └── seeders/        # Seeders
├── resources/
│   ├── views/          # Vistas Blade
│   │   ├── livewire/   # Componentes Livewire
│   │   └── components/ # Componentes reutilizables
│   ├── css/           # Estilos (Tailwind)
│   └── js/            # JavaScript (Alpine, Chart.js)
├── routes/
│   └── web.php        # Rutas web
├── public/
│   └── images/        # Assets públicos
└── package.json       # Dependencias Node.js
```

---

## Características del Sistema

### Funcionalidades Principales

1. **Sistema de Tickets**
   - Crear, editar y eliminar tickets
   - Adjuntar evidencias (imágenes, archivos)
   - Sistema de prioridades (Baja, Media, Alta)
   - Estados (Pendiente, En Proceso, Resuelto)

2. **Dashboard Administrativo**
   - KPIs en tiempo real
   - Gráficos con Chart.js
   - Gestión de todos los tickets
   - Historial de cambios de estado

3. **Auto-Archivo de Tickets**
   - Los tickets resueltos se ocultan automáticamente después de 7 días
   - Solo para empleados (los admins ven todo)

4. **Notificaciones en Tiempo Real**
   - Laravel Reverb (WebSockets)
   - Notificaciones de cambios de estado

5. **Paleta Corporativa**
   - Colores Cloud Technologies
   - Diseño profesional y consistente

---

## Solución de Problemas Comunes

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000] [1045] Access denied"
Verifica las credenciales de la base de datos en `.env`.

### Error: "npm run dev" no funciona
```bash
rm -rf node_modules package-lock.json
npm install
npm run dev
```

### Vite no recarga cambios
```bash
# Limpia caché y reinicia
npm run build
npm run dev
```

### Problema con storage/logs
```bash
chmod -R 775 storage bootstrap/cache
```

---

## Tecnologías Utilizadas

| Tecnología | Versión | Propósito |
|-----------|---------|-----------|
| Laravel | 11.x | Framework PHP |
| Livewire | 3.x | Componentes reactivos |
| Tailwind CSS | 4.x | Estilos CSS |
| Alpine.js | 3.x | JavaScript reactivo |
| Chart.js | 4.4.0 | Gráficos |
| Laravel Reverb | 1.x | WebSockets |
| PostgreSQL | 18.1 | Base de datos |
| Vite | 7.x | Build tool |

---



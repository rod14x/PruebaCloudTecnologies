# Estructura de Base de Datos - Sistema de Gestion de Incidentes

## Resumen

Base de datos relacional en PostgreSQL con esquema `gestionIncidentes`. Implementa un sistema completo de gestion de tickets con roles, seguimiento, auditoria y adjuntos.

## Tablas

### 1. users
Tabla de usuarios del sistema con autenticacion.

| Campo | Tipo | Descripcion |
|-------|------|-------------|
| id | BIGINT | Clave primaria |
| name | VARCHAR(255) | Nombre del usuario |
| email | VARCHAR(255) | Correo electronico (unique) |
| password | VARCHAR(255) | Contraseña hasheada |
| rol | TINYINT | 0=Empleado, 1=Administrador |
| email_verified_at | TIMESTAMP | Fecha de verificacion de email |
| remember_token | VARCHAR(100) | Token de sesion |
| created_at | TIMESTAMP | Fecha de creacion |
| updated_at | TIMESTAMP | Fecha de actualizacion |

**Indices:**
- PRIMARY KEY (id)
- UNIQUE (email)

---

### 2. categorias
Clasificacion de tickets por tipo de incidente.

| Campo | Tipo | Descripcion |
|-------|------|-------------|
| id | BIGINT | Clave primaria |
| nombre | VARCHAR(255) | Nombre de la categoria (unique) |
| descripcion | TEXT | Descripcion detallada (nullable) |
| created_at | TIMESTAMP | Fecha de creacion |
| updated_at | TIMESTAMP | Fecha de actualizacion |

**Indices:**
- PRIMARY KEY (id)
- UNIQUE (nombre)

**Ejemplos:** Hardware, Software, Red, Base de Datos, Seguridad

---

### 3. tickets
Registro principal de incidentes/tickets.

| Campo | Tipo | Descripcion |
|-------|------|-------------|
| id | BIGINT | Clave primaria |
| titulo | VARCHAR(255) | Titulo del incidente |
| descripcion | TEXT | Descripcion detallada |
| categoria_id | BIGINT | FK hacia categorias |
| prioridad | TINYINT | 0=Baja, 1=Media, 2=Alta |
| estado | TINYINT | 0=Pendiente, 1=En Proceso, 2=Resuelto |
| usuario_id | BIGINT | FK hacia users (creador) |
| created_at | TIMESTAMP | Fecha de creacion |
| updated_at | TIMESTAMP | Fecha de actualizacion |

**Indices:**
- PRIMARY KEY (id)
- FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE CASCADE
- FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT

**Valores de enumeracion:**
- Prioridad: 0 (Baja), 1 (Media), 2 (Alta)
- Estado: 0 (Pendiente), 1 (En Proceso), 2 (Resuelto)

---

### 4. comentarios
Seguimiento y comunicacion dentro de cada ticket.

| Campo | Tipo | Descripcion |
|-------|------|-------------|
| id | BIGINT | Clave primaria |
| ticket_id | BIGINT | FK hacia tickets |
| usuario_id | BIGINT | FK hacia users (autor del comentario) |
| contenido | TEXT | Contenido del comentario |
| created_at | TIMESTAMP | Fecha de creacion |
| updated_at | TIMESTAMP | Fecha de actualizacion |

**Indices:**
- PRIMARY KEY (id)
- FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE
- FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE CASCADE

---

### 5. archivos_adjuntos
Archivos de evidencia asociados a tickets.

| Campo | Tipo | Descripcion |
|-------|------|-------------|
| id | BIGINT | Clave primaria |
| ticket_id | BIGINT | FK hacia tickets |
| nombre_archivo | VARCHAR(255) | Nombre original del archivo |
| ruta_archivo | VARCHAR(255) | Ruta de almacenamiento |
| tamano | INTEGER | Tamaño en bytes (nullable) |
| created_at | TIMESTAMP | Fecha de subida |
| updated_at | TIMESTAMP | Fecha de actualizacion |

**Indices:**
- PRIMARY KEY (id)
- FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE

---

### 6. historial_estados
Auditoria de cambios de estado en tickets.

| Campo | Tipo | Descripcion |
|-------|------|-------------|
| id | BIGINT | Clave primaria |
| ticket_id | BIGINT | FK hacia tickets |
| usuario_id | BIGINT | FK hacia users (quien hizo el cambio) |
| estado_anterior | TINYINT | Estado previo (0, 1, 2) |
| estado_nuevo | TINYINT | Estado nuevo (0, 1, 2) |
| created_at | TIMESTAMP | Fecha del cambio |
| updated_at | TIMESTAMP | Fecha de actualizacion |

**Indices:**
- PRIMARY KEY (id)
- FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE
- FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE CASCADE

---

## Relaciones

### Usuario → Tickets (1:N)
Un usuario puede crear multiples tickets.
```
users.id → tickets.usuario_id
```

### Usuario → Comentarios (1:N)
Un usuario puede escribir multiples comentarios.
```
users.id → comentarios.usuario_id
```

### Usuario → Historial (1:N)
Un usuario puede realizar multiples cambios de estado.
```
users.id → historial_estados.usuario_id
```

### Categoria → Tickets (1:N)
Una categoria puede tener multiples tickets.
```
categorias.id → tickets.categoria_id
```

### Ticket → Comentarios (1:N)
Un ticket puede tener multiples comentarios.
```
tickets.id → comentarios.ticket_id
```

### Ticket → Archivos (1:N)
Un ticket puede tener multiples archivos adjuntos.
```
tickets.id → archivos_adjuntos.ticket_id
```

### Ticket → Historial (1:N)
Un ticket puede tener multiples registros de cambio de estado.
```
tickets.id → historial_estados.ticket_id
```

---

## Diagrama de Relaciones

```
users
  ├── tickets (1:N)
  ├── comentarios (1:N)
  └── historial_estados (1:N)

categorias
  └── tickets (1:N)

tickets
  ├── comentarios (1:N)
  ├── archivos_adjuntos (1:N)
  └── historial_estados (1:N)
```

---

## Optimizaciones Implementadas

1. **Uso de TINYINT en lugar de VARCHAR:**
   - Campo `rol`: Ahorro de ~8-12 bytes por usuario
   - Campos `prioridad` y `estado`: Ahorro de ~6-10 bytes por ticket
   - Campos `estado_anterior` y `estado_nuevo`: Ahorro de ~12-20 bytes por registro
   - Total estimado: ~40-50 bytes por ticket + historial

2. **Indices estrategicos:**
   - Unique en email y nombre de categoria
   - Foreign keys para integridad referencial
   - Cascada en deletes para mantener consistencia

3. **ON DELETE policies:**
   - CASCADE: tickets, comentarios, archivos, historial (datos dependientes)
   - RESTRICT: categorias (prevenir eliminacion con tickets asociados)

---

## Notas de Implementacion

- Esquema: `gestionIncidentes`
- Motor: PostgreSQL 18.1
- Charset: UTF-8
- Timestamps automaticos en todas las tablas
- Soft deletes: No implementado (puede agregarse si se requiere)

---

## Comandos de Migracion

```bash
# Ejecutar migraciones
php artisan migrate

# Revertir ultima migracion
php artisan migrate:rollback

# Revertir todas y re-ejecutar
php artisan migrate:fresh

# Ver estado de migraciones
php artisan migrate:status
```

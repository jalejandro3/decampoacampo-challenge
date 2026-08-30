# deCampoaCampo

## Backend

API REST para la gestión de productos (CRUD), desarrollada en **PHP 8.4 nativo** (sin frameworks de aplicación), con arquitectura por capas, tests automatizados y contenerización con Docker.

## Requisitos

- [Docker](https://www.docker.com/) y Docker Compose

No necesitas PHP ni MySQL instalados en tu máquina: todo corre dentro de contenedores.

## Puesta en marcha

### 1. Configurar las variables de entorno

El proyecto usa dos archivos de configuración, ambos generados a partir de sus plantillas. **Este paso es obligatorio y debe hacerse antes de levantar Docker.**

**a) Configuración de la aplicación**

Crea el archivo `.env` a partir de la plantilla:

```bash
cp .env.example .env
```

Edita el `.env` y completa las variables con estos valores:

| Variable | Valor | Descripción |
|----------|-------|-------------|
| `PRECIO_USD` | `1485.00` | Cotización del dólar. Se usa para calcular el campo `precio_usd` de cada producto. |
| `DB_HOST` | `mysql` | Host de la base de datos (el nombre del servicio en la red de Docker). |
| `DB_PORT` | `3306` | Puerto interno de MySQL dentro de la red de Docker. |
| `DB_NAME` | `decampoacampo` | Nombre de la base de datos. |
| `DB_USER` | `root` | Usuario de la base de datos. |
| `DB_PASS` | `root` | Contraseña de la base de datos. |

**b) Configuración de MySQL**

Crea el archivo `mysql.env` a partir de su plantilla:

```bash
cp docker/mysql/mysql.env.example docker/mysql/mysql.env
```

Edita el `mysql.env` y completa:

| Variable | Valor | Descripción |
|----------|-------|-------------|
| `MYSQL_ROOT_PASSWORD` | `root` | Contraseña del usuario root de MySQL. Debe coincidir con `DB_PASS` del archivo `.env`. |

> **Nota sobre la red de Docker:** `DB_HOST` es `mysql` (el nombre del servicio) y `DB_PORT` es `3306` (el puerto interno), porque dentro de la red de Docker los contenedores se comunican por nombre de servicio y puerto interno. El puerto `3307` se expone al host únicamente para inspeccionar la base de datos desde tu máquina si lo necesitas.

### 2. Levantar el entorno

Desde la raíz del proyecto:

```bash
docker-compose up --build
```

Esto construye la imagen de la aplicación (PHP 8.4 + Apache) y levanta dos contenedores:

- **php**: la aplicación, servida por Apache en `http://localhost:8080`
- **mysql**: la base de datos MySQL 8.0

La primera vez, MySQL tarda unos segundos en inicializarse y crear la base de datos y las tablas automáticamente (mediante el script `init.sql`). Si la primera petición falla con un error de conexión, espera unos segundos y reintenta.

### 3. Probar la API

Una vez levantado el entorno, la API responde en `http://localhost:8080`. Ejemplos con `curl`:

**Listar todos los productos**
```bash
curl -i http://localhost:8080/productos
```

**Obtener un producto por id**
```bash
curl -i http://localhost:8080/productos/1
```

**Crear un producto**
```bash
curl -i -X POST http://localhost:8080/productos \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Ganado","descripcion":"Maute","precio":2000000}'
```

**Actualizar un producto**
```bash
curl -i -X PUT http://localhost:8080/productos/1 \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Vaca","descripcion":"Lechera","precio":300000}'
```

**Eliminar un producto**
```bash
curl -i -X DELETE http://localhost:8080/productos/1
```

## Endpoints

| Método | Ruta | Descripción | Éxito |
|--------|------|-------------|-------|
| `GET` | `/productos` | Lista todos los productos | `200` |
| `GET` | `/productos/{id}` | Obtiene un producto por id | `200` |
| `POST` | `/productos` | Crea un producto | `201` |
| `PUT` | `/productos/{id}` | Actualiza un producto | `200` |
| `DELETE` | `/productos/{id}` | Elimina un producto | `204` |

Cada producto devuelto incluye el campo `precio_usd`, calculado dividiendo el precio (en pesos argentinos) entre `PRECIO_USD`.

### Códigos de error

La API distingue dos niveles de validación:

- **`400 Bad Request`** — la petición está mal formada (falta alguna clave requerida en el cuerpo, o el JSON es inválido).
- **`422 Unprocessable Content`** — la petición está bien formada, pero algún valor viola una regla de negocio (por ejemplo, precio menor o igual a 0, o nombre/descripción vacíos). El mensaje indica el problema concreto.
- **`404 Not Found`** — el producto solicitado no existe.
- **`500 Internal Server Error`** — error inesperado (el mensaje es genérico, para no exponer detalles internos).

## Frontend

Interfaz web en HTML, CSS y JavaScript puro (sin frameworks) que consume la API REST de arriba para listar, crear, editar y eliminar productos, mostrando el precio en pesos argentinos y en dólares.

### Estructura de archivos

```
public/
  ├── index.html
  ├── css/
  │   └── estilos.css
  └── js/
      ├── api.js       — llamadas fetch a los 5 endpoints
      ├── dom.js       — referencias a los elementos del DOM
      ├── render.js    — construcción de la tabla de productos
      └── app.js       — lógica de la interfaz (eventos, formulario, mensajes)
```

### Cómo ejecutarlo

El frontend se sirve desde el mismo `public/` que la API (mismo origen, sin CORS necesario) — no hace falta ningún paso extra ni otro servidor. Con el entorno ya levantado (`docker-compose up --build`, ver sección Backend), abrí:

```
http://localhost:8080/
```

### Cómo probar la interacción con la API

| Acción en la UI | Qué dispara |
  |---|---|
| Cargar la página | `GET /productos` — llena la tabla automáticamente |
| Botón "Nuevo producto" → completar form → "Guardar" | `POST /productos` |
| Botón "Actualizar" en una fila → editar campos → "Guardar" | `GET /productos/{id}` (precarga) + `PUT /productos/{id}` |
| Botón "Eliminar" en una fila → confirmar en el modal | `DELETE /productos/{id}` |

Los mensajes de éxito o error de cada acción aparecen arriba de la tabla (verde/rojo) y se ocultan solos a los 2 segundos. Para verificar el manejo de errores desde la UI:

- Crear un producto con nombre o descripción vacíos, o precio 0/negativo → debería mostrar el mensaje de validación que devuelve la API (422).
- Eliminar un producto y volver a intentar eliminarlo (o editarlo) → debería mostrar el mensaje de "no existe" (404).
- Con el contenedor `php` apagado, cualquier acción debería mostrar un mensaje de error de conexión, sin romper la página.

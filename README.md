# Proyecto de Investigación — Versión 1

## Integrantes

- Ángel David Gutiérrez Ladino
- Miguel Ángel Lotero Álvarez

Proyecto académico desarrollado en **PHP puro**, **MariaDB** y **Docker** para
el módulo de Investigación.

La versión 1 trabaja únicamente con las seis tablas del módulo que no poseen
claves foráneas.

El sistema está dividido en dos aplicaciones independientes:

- **Backend:** API REST desarrollada en PHP.
- **Frontend:** aplicación PHP que consume la API mediante HTTP.

El frontend no se conecta directamente a MariaDB.

---

## Estado de la versión

La **v1 se encuentra en desarrollo**.

Actualmente se encuentra configurado el entorno Docker con:

- MariaDB;
- API de Investigación;
- frontend PHP;
- phpMyAdmin.

La implementación de los CRUD de los seis recursos se realiza de forma
progresiva siguiendo la documentación del Spec Kit.

---

## Cómo levantar el proyecto

Desde la raíz del repositorio backend:

```powershell
docker compose up -d --build
```

Para comprobar que los contenedores están activos:

```powershell
docker compose ps
```

Para detenerlos:

```powershell
docker compose down
```

## Accesos locales

| Servicio | Dirección |
|---|---|
| Frontend | http://localhost:8110 |
| API | http://localhost:8111 |
| phpMyAdmin | http://localhost:8105 |
| MariaDB | `localhost:13330` |

El endpoint raíz de la API permite comprobar que el servicio está activo:

http://localhost:8111/

## Qué incluye la v1

La versión 1 implementa CRUD para los seis recursos del módulo de Investigación
que no tienen claves foráneas.

| Recurso | Llave primaria | Datos principales |
|---|---|---|
| `area_conocimiento` | `id` | gran área, área y disciplina |
| `objetivo_desarrollo_sostenible` | `id` | nombre y categoría |
| `area_aplicacion` | `id` | nombre |
| `termino_clave` | `termino` | término y término en inglés |
| `universidad` | `id` | nombre, tipo y ciudad |
| `linea_investigacion` | `id` autoincremental | nombre y descripción |

## Estructura principal del backend

```
api_investigacion/
├── index.php
├── Dockerfile
├── controladores/
├── excepciones/
├── modelos/
├── pruebas/
├── repositorios/
└── servicios/
    └── ensamblador.php

db/
└── init.sql

docs/
└── spec_kit/

docker-compose.yml
.env.example
.gitignore
README.md
```

### api_investigacion/index.php

Es el Front Controller de la API.

Todas las peticiones HTTP entran por este archivo y desde allí se decide qué
controlador debe atender cada ruta.

No contiene SQL ni reglas de negocio.

### controladores/

Contiene los controladores específicos de los recursos de la v1.
Sus responsabilidades principales son:
- recibir las peticiones HTTP;
- validar la forma de los datos recibidos;
- llamar a los servicios correspondientes;
- traducir resultados y errores a respuestas HTTP y JSON.
Los controladores no acceden directamente a MariaDB.

### servicios/

Contiene las interfaces y las implementaciones de las reglas de negocio.

Los servicios no contienen SQL ni conocen detalles del protocolo HTTP.

### servicios/ensamblador.php

Es el punto encargado de crear y conectar las implementaciones concretas de
repositorios y servicios.

Permite mantener separadas las capas y centralizar la creación de
dependencias.

### repositorios/

Contiene las interfaces de repositorio y las implementaciones que acceden a
MariaDB mediante PDO.

Esta es la única capa encargada de ejecutar SQL.

Las consultas utilizan prepared statements.

### modelos/

Contiene las clases que representan los recursos utilizados por la v1.

### excepciones/
Contiene las excepciones utilizadas para representar situaciones de negocio
sin mezclar códigos HTTP dentro de los servicios.

### pruebas/

Contiene las pruebas utilizadas para comprobar el comportamiento de las capas
del backend.

### db/init.sql

Contiene la estructura de la base de datos y los datos de referencia
utilizados por el proyecto.

### docs/spec_kit/

Contiene la documentación de especificación, diseño, contratos, pruebas y
tareas de la versión.

### docker-compose.yml

Define los servicios necesarios para ejecutar el entorno local del proyecto,
incluyendo MariaDB, la API y phpMyAdmin.

### .env.example

Documenta las variables de entorno necesarias para ejecutar el proyecto sin
guardar credenciales reales dentro del repositorio.

## Endpoints de la API

Cada recurso de la v1 posee rutas específicas.

No se utiliza una ruta genérica como:

```text
/api/{tabla}
```

Por ejemplo, area_conocimiento utiliza:

GET    /api/area_conocimiento
POST   /api/area_conocimiento
GET    /api/area_conocimiento/{id}
PUT    /api/area_conocimiento/{id}
PATCH  /api/area_conocimiento/{id}
DELETE /api/area_conocimiento/{id}

La misma estructura se aplica a los demás recursos de la v1:

/api/objetivo_desarrollo_sostenible
/api/area_aplicacion
/api/termino_clave
/api/universidad
/api/linea_investigacion

Las operaciones disponibles son:
- GET: listar registros u obtener un registro específico.
- POST: crear un nuevo registro.
- PUT: reemplazar completamente los campos editables de un registro.
- PATCH: modificar únicamente los campos enviados.
- DELETE: retirar lógicamente un registro.
Cada recurso mantiene sus propias rutas, validaciones y reglas según su
estructura de datos.

## Borrado lógico

La API no elimina físicamente los registros de MariaDB.

Las tablas utilizadas en la v1 poseen el campo:

```sql
activo BOOLEAN NOT NULL DEFAULT TRUE
```

Cuando se ejecuta una operación DELETE, el registro se marca como inactivo:

```sql
UPDATE ...
SET activo = FALSE
WHERE ...
  AND activo = TRUE;
```

La fila permanece almacenada en la base de datos, pero deja de aparecer en las
consultas normales de la API.

Los SELECT utilizados por los repositorios trabajan únicamente con registros
activos:

```sql
WHERE activo = TRUE
```

Desde el punto de vista de la API, un registro retirado se comporta como un
recurso que ya no está disponible.

El campo activo es interno y no puede ser enviado ni modificado directamente
mediante POST, PUT o PATCH.
# Especificación — v1: Investigación

## 1. Qué construye esta versión

La versión 1 construye el CRUD completo, tanto en la API REST como en el
frontend PHP, para las seis tablas del módulo de Investigación que no tienen
claves foráneas:

- `area_conocimiento`
- `objetivo_desarrollo_sostenible`
- `area_aplicacion`
- `termino_clave`
- `universidad`
- `linea_investigacion`

Cada recurso tendrá endpoints específicos y su correspondiente interfaz en el
frontend.

La API se implementa en PHP y se conecta a MariaDB mediante PDO. El frontend
también se implementa en PHP, pero consume la API únicamente por HTTP y no se
conecta directamente a la base de datos.

El borrado de los registros es lógico: una operación DELETE marca el registro
como inactivo y los listados solo muestran registros activos.


## 2. Lo que esta versión NO incluye

Esta versión no incluye:

- Las diez tablas del módulo que tienen claves foráneas.
- Las tablas de gestión de usuarios (`usuario`, `rol` y `rol_usuario`).
- Autenticación con JWT.
- Control de acceso por roles.
- Consultas multitabla.
- Dashboard y gráficos.
- Publicación en servidor.
- Funcionalidades propias de las versiones 2, 3 y 4.

La v1 se limita a los seis recursos sin claves foráneas definidos para esta
versión.


## 3. Recursos de la versión

### 3.1 `area_conocimiento`

| Campo | Tipo | Obligatorio | Nota |
|---|---|---|---|
| `id` | texto, hasta 6 caracteres | Sí | Llave primaria. Código alfanumérico |
| `gran_area` | texto, hasta 60 caracteres | Sí | — |
| `area` | texto, hasta 60 caracteres | Sí | — |
| `disciplina` | texto, hasta 150 caracteres | Sí | — |

La tabla inicia con 218 registros cargados desde los datos de referencia.

`activo` no forma parte de la ficha que el usuario crea o modifica. Es un
campo interno utilizado para implementar el borrado lógico.


### 3.2 `objetivo_desarrollo_sostenible`

| Campo | Tipo | Obligatorio | Nota |
|---|---|---|---|
| `id` | entero | Sí | Llave primaria |
| `nombre` | texto, hasta 60 caracteres | Sí | — |
| `categoria` | texto, hasta 45 caracteres | Sí | — |

La tabla inicia con 17 registros correspondientes a los Objetivos de
Desarrollo Sostenible.

`activo` se utiliza únicamente para el borrado lógico.


### 3.3 `area_aplicacion`

| Campo | Tipo | Obligatorio | Nota |
|---|---|---|---|
| `id` | entero | Sí | Llave primaria |
| `nombre` | texto, hasta 150 caracteres | Sí | — |

La tabla inicia con 21 registros cargados desde los datos de referencia.

`activo` se utiliza únicamente para el borrado lógico.


### 3.4 `termino_clave`

| Campo | Tipo | Obligatorio | Nota |
|---|---|---|---|
| `termino` | texto, hasta 30 caracteres | Sí | Llave primaria |
| `termino_ingles` | texto, hasta 30 caracteres | No | Traducción al inglés |

La llave primaria de este recurso es `termino`, no un campo llamado `id`.

La tabla no tiene datos iniciales definidos en el documento del módulo.

`activo` se utiliza únicamente para el borrado lógico.


### 3.5 `universidad`

| Campo | Tipo | Obligatorio | Nota |
|---|---|---|---|
| `id` | entero | Sí | Llave primaria |
| `nombre` | texto, hasta 60 caracteres | Sí | — |
| `tipo` | texto, hasta 45 caracteres | Sí | — |
| `ciudad` | texto, hasta 45 caracteres | Sí | — |

El documento del módulo indica que la tabla debe iniciar con 6 registros
provenientes de los datos de referencia.

`activo` se utiliza únicamente para el borrado lógico.


### 3.6 `linea_investigacion`

| Campo | Tipo | Obligatorio | Nota |
|---|---|---|---|
| `id` | entero autoincremental | Generado por la base | Llave primaria |
| `nombre` | texto, hasta 45 caracteres | Sí | — |
| `descripcion` | texto, hasta 256 caracteres | Sí | — |

A diferencia de los demás recursos con llave `id`, esta tabla genera su
identificador automáticamente.

La tabla no tiene datos iniciales definidos en el documento del módulo.

`activo` se utiliza únicamente para el borrado lógico.


## 4. Requisitos funcionales

### RF1 — Listar registros

La API debe permitir listar los registros activos de cada uno de los seis
recursos de la v1.

Los registros marcados como inactivos no deben aparecer en los listados.


### RF2 — Obtener un registro

La API debe permitir consultar un registro individual mediante su llave
primaria.

Para `termino_clave`, la llave utilizada será `termino`. Para los demás
recursos se utilizará `id`.

Un registro retirado debe comportarse frente a la API como un registro que no
se encuentra disponible.


### RF3 — Crear un registro

La API debe permitir crear registros en cada uno de los seis recursos.

Los campos obligatorios dependen de la estructura de cada tabla definida en
la sección 3.

En `linea_investigacion`, el `id` es generado automáticamente por MariaDB y no
debe ser enviado como dato obligatorio de creación.


### RF4 — Reemplazar un registro

La API debe permitir reemplazar completamente un registro existente mediante
PUT.

La operación debe exigir todos los campos obligatorios del recurso, excepto la
llave primaria cuando esta sea enviada mediante la ruta.


### RF5 — Actualizar parcialmente un registro

La API debe permitir modificar parcialmente un registro mediante PATCH.

Solo deben modificarse los campos enviados en la petición. Los campos que no
se envíen deben conservar su valor actual.


### RF6 — Retirar un registro

La API debe permitir retirar registros mediante DELETE.

El borrado debe ser lógico:

- el registro permanece almacenado;
- su campo `activo` pasa a falso;
- deja de aparecer en los listados;
- una nueva consulta normal no debe tratarlo como activo.


### RF7 — Interfaz de usuario

El frontend debe permitir realizar las operaciones de la v1 sobre los seis
recursos mediante pantallas PHP.

El frontend debe consumir la API por HTTP y no debe conectarse directamente a
MariaDB.

Si la API no está disponible, el frontend debe continuar respondiendo y
mostrar un aviso indicando que el servicio no está disponible.


## 5. Criterios de aceptación

1. Los seis recursos de la v1 tienen CRUD funcional en la API.
2. Los seis recursos tienen su correspondiente interfaz en el frontend.
3. Los listados muestran solamente registros activos.
4. DELETE realiza borrado lógico y no elimina físicamente la fila.
5. Los registros retirados dejan de aparecer en los listados.
6. PUT exige la información completa requerida para reemplazar un registro.
7. PATCH permite modificar solamente los campos enviados.
8. El frontend consume la API mediante HTTP y no utiliza PDO ni credenciales
   de la base de datos.
9. Con la API apagada y la base encendida, el frontend continúa respondiendo
   pero no muestra datos provenientes de la base.
10. Los datos iniciales esperados están disponibles:
    - `area_conocimiento`: 218 registros;
    - `objetivo_desarrollo_sostenible`: 17 registros;
    - `area_aplicacion`: 21 registros;
    - `universidad`: 6 registros.
11. `termino_clave` y `linea_investigacion` pueden iniciar sin registros.
12. Todo lo construido en esta versión corresponde únicamente al alcance de
    la v1.


## 6. Clarificaciones


### C1 — Identificadores de los recursos

`area_conocimiento`, `objetivo_desarrollo_sostenible`, `area_aplicacion` y
`universidad` reciben su llave primaria como parte del registro.

`termino_clave` utiliza `termino` como llave primaria.

`linea_investigacion` utiliza un `id` autoincremental generado por MariaDB.


### C2 — Borrado lógico

Las seis tablas de la v1 utilizan el campo `activo`.

DELETE no elimina físicamente el registro. Cambia `activo` a falso y todas las
consultas normales deben filtrar los registros inactivos.


### C3 — Datos iniciales de universidad

El documento del módulo establece que `universidad` debe iniciar con 6 registros
provenientes de los datos de referencia.

El `init.sql` actual todavía no contiene esos INSERT, por lo que los seis
registros deberán incorporarse durante la implementación de la v1.

Esto no modifica el alcance de la versión: forma parte de los datos iniciales
requeridos para `universidad`.


### C4 — Códigos HTTP de la v1

Los seis recursos utilizan los mismos criterios generales de respuesta:

- `GET` exitoso → **200 OK**.
- Listado sin registros activos → **204 No Content**.
- `POST` exitoso → **201 Created**.
- `PUT` exitoso → **200 OK**.
- `PATCH` exitoso → **200 OK**.
- `DELETE` exitoso → **200 OK**.
- Parámetros válidos en forma pero inválidos para la operación → **400 Bad Request**.
- Recurso inexistente o retirado → **404 Not Found**.
- Cuerpo con campos obligatorios faltantes, tipos incorrectos o datos inválidos
  → **422 Unprocessable Entity**.
- Error inesperado del servidor o de la base de datos → **500 Internal Server Error**.

Estos códigos se aplican de forma consistente a los seis CRUD de la v1.


### C5 — Llaves primarias duplicadas

Cuando se intente crear un registro cuya llave primaria ya exista, la API
responderá **409 Conflict**.

Esto aplica a las llaves suministradas por el cliente, por ejemplo:

- `area_conocimiento.id`
- `objetivo_desarrollo_sostenible.id`
- `area_aplicacion.id`
- `termino_clave.termino`
- `universidad.id`

`linea_investigacion` no recibe su `id` en el POST porque MariaDB lo genera
automáticamente mediante `AUTO_INCREMENT`.

El conflicto de llave duplicada se diferencia de un error interno inesperado,
que responderá **500 Internal Server Error**.


### C6 — Rutas específicas por recurso

La API no utilizará una ruta genérica como `/api/{tabla}`.

Cada recurso de la v1 tendrá rutas específicas:

- `/api/area_conocimiento`
- `/api/objetivo_desarrollo_sostenible`
- `/api/area_aplicacion`
- `/api/termino_clave`
- `/api/universidad`
- `/api/linea_investigacion`

Cada una tendrá sus operaciones GET, POST, PUT, PATCH y DELETE según
corresponda.

Los contratos exactos de cada ruta se documentarán en `6_contracts.md`.
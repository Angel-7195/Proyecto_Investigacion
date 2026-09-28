# Contratos de la API — v1: Investigación

Base local: definida por el entorno. Las rutas de este documento son relativas
a la raíz de la API.

Los valores utilizados en los ejemplos son ilustrativos. No representan
necesariamente los datos iniciales cargados en `db/init.sql`.


## §0. El sobre, siempre el mismo

**Éxito de listado:**

```json
{
  "recurso": "universidad",
  "total": 2,
  "datos": [ ... ]
}
```

**Éxito de creación:**

```json
{
  "estado": 201,
  "mensaje": "Universidad creada exitosamente.",
  "datos": { ... }
}
```

**Éxito de modificación o retiro:**

```json
{
  "estado": 200,
  "mensaje": "...",
  "filasAfectadas": 1
}
```

**Error de validación:**

```json
{
  "estado": 422,
  "mensaje": "Datos inválidos.",
  "errores": [
    "El campo nombre es obligatorio."
  ]
}
```

`errores[]` aparece solamente en el 422, porque una petición puede tener más
de un problema de forma al mismo tiempo.

Los demás errores utilizan `detalle`:

```json
{
  "estado": 404,
  "mensaje": "Universidad no encontrada.",
  "detalle": "No existe la universidad solicitada."
}
```

El campo `activo` nunca aparece como dato editable en POST, PUT o PATCH.

Las llaves primarias tampoco se reciben en PUT o PATCH: la llave que identifica
la fila viaja en la ruta.


### La traducción de excepciones a códigos

| Qué pasa | Quién lo detecta | Código |
|---|---|---|
| El cuerpo no tiene la forma requerida | El **controlador**, antes de llamar al servicio | **422** |
| Se envía un campo no permitido | El **controlador** | **422** |
| Una operación válida en forma no tiene sentido, por ejemplo PATCH `{}` | El **servicio** (`InvalidArgumentException`) | **400** |
| La fila no existe o ya está retirada | El **servicio** (`NoEncontradoExcepcion`) | **404** |
| La llave primaria ya está ocupada | El **repositorio**, que traduce el conflicto a `ConflictoExcepcion` | **409** |
| La ruta existe pero no acepta ese método | El **enrutador** | **405** |
| Ocurre un error inesperado o MariaDB no está disponible | Manejo general de `Throwable` | **500** |

Una fila retirada responde 404 en las consultas normales aunque físicamente
continúe almacenada en MariaDB.

Una llave retirada sigue ocupada. Intentar crear otra fila con esa misma llave
responde 409.


---

## 1. `GET /` — diagnóstico

```text
→ 200 {
    "mensaje":"API de Investigación funcionando",
    "version":"v1",
    "recursos":[
        "area_conocimiento",
        "objetivo_desarrollo_sostenible",
        "area_aplicacion",
        "termino_clave",
        "universidad",
        "linea_investigacion"
    ]
}
```

Este endpoint no consulta una tabla concreta. Sirve para comprobar que la API
está levantada y que corresponde a la v1.


---

# 2. `area_conocimiento`

## 2.1 `GET /api/area_conocimiento` — listar

```text
→ 200 {
    "recurso":"area_conocimiento",
    "total":218,
    "datos":[ ... ]
}

→ 204 sin cuerpo
```

El 204 se utiliza cuando no existen filas activas.

**Solo devuelve las activas.** Una ficha retirada no aparece aunque continúe
almacenada en MariaDB.


## 2.2 `GET /api/area_conocimiento/{id}` — obtener una

```text
→ 200 {
    "id":"9Z01",
    "gran_area":"Ingeniería y Tecnología",
    "area":"Ingeniería de Sistemas",
    "disciplina":"Ingeniería de software"
}

→ 404 {
    "estado":404,
    "mensaje":"Área de conocimiento no encontrada.",
    "detalle":"No existe un área de conocimiento activa con el id solicitado."
}
```


## 2.3 `POST /api/area_conocimiento` — crear

Cuerpo: **todos los campos de la ficha, incluida la llave**.

```json
{
  "id": "9Z01",
  "gran_area": "Ingeniería y Tecnología",
  "area": "Ingeniería de Sistemas",
  "disciplina": "Ingeniería de software"
}
```

```text
→ 201 {
    "estado":201,
    "mensaje":"Área de conocimiento creada exitosamente.",
    "datos":{ ... }
}

→ 422 {
    "estado":422,
    "mensaje":"Datos inválidos.",
    "errores":["El campo gran_area es obligatorio."]
}

→ 409 {
    "estado":409,
    "mensaje":"Conflicto de datos.",
    "detalle":"Ya existe un área de conocimiento con esa llave."
}
```


## 2.4 `PUT /api/area_conocimiento/{id}` — reemplazar

Cuerpo: **todos los campos menos la llave**, que ya está en la ruta.

```json
{
  "gran_area": "Ingeniería y Tecnología",
  "area": "Ingeniería de Sistemas",
  "disciplina": "Ingeniería de software"
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Área de conocimiento reemplazada.",
    "filasAfectadas":1
}

→ 422 { ..., "errores":["El campo gran_area es obligatorio."] }

→ 404 {
    "estado":404,
    "mensaje":"Área de conocimiento no encontrada.",
    "detalle":"No existe un área de conocimiento activa con el id solicitado."
}
```


## 2.5 `PATCH /api/area_conocimiento/{id}` — actualizar

Cuerpo: **solo los campos que se quieran cambiar**.

```json
{
  "disciplina": "Ingeniería de software"
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Área de conocimiento actualizada.",
    "filasAfectadas":1
}

→ 400 {
    "estado":400,
    "mensaje":"Parámetros inválidos.",
    "detalle":"No se envió ningún campo para actualizar."
}

→ 404 { ... }
```

El cuerpo vacío `{}` es **400, no 422**: el JSON tiene una forma válida, pero
la operación no tiene nada que modificar.


## 2.6 `DELETE /api/area_conocimiento/{id}` — retirar

```text
→ 200 {
    "estado":200,
    "mensaje":"Área de conocimiento retirada.",
    "filasAfectadas":1
}

→ 404 { ... }    ← un segundo DELETE sobre la misma llave
```

El borrado es lógico. La fila permanece almacenada con `activo = FALSE`.


---

# 3. `objetivo_desarrollo_sostenible`

## 3.1 `GET /api/objetivo_desarrollo_sostenible` — listar

```text
→ 200 {
    "recurso":"objetivo_desarrollo_sostenible",
    "total":17,
    "datos":[ ... ]
}

→ 204 sin cuerpo
```

Solo devuelve registros activos.


## 3.2 `GET /api/objetivo_desarrollo_sostenible/{id}` — obtener uno

```text
→ 200 {
    "id":1,
    "nombre":"Fin de la Pobreza",
    "categoria":"Social"
}

→ 404 {
    "estado":404,
    "mensaje":"Objetivo de desarrollo sostenible no encontrado.",
    "detalle":"No existe un objetivo activo con el id solicitado."
}
```


## 3.3 `POST /api/objetivo_desarrollo_sostenible` — crear

```json
{
  "id": 18,
  "nombre": "Objetivo de ejemplo",
  "categoria": "Social"
}
```

```text
→ 201 {
    "estado":201,
    "mensaje":"Objetivo de desarrollo sostenible creado exitosamente.",
    "datos":{ ... }
}

→ 422 { ..., "errores":[ ... ] }

→ 409 {
    "estado":409,
    "mensaje":"Conflicto de datos.",
    "detalle":"Ya existe un objetivo de desarrollo sostenible con esa llave."
}
```


## 3.4 `PUT /api/objetivo_desarrollo_sostenible/{id}` — reemplazar

```json
{
  "nombre": "Objetivo actualizado",
  "categoria": "Social"
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Objetivo de desarrollo sostenible reemplazado.",
    "filasAfectadas":1
}

→ 422 { ..., "errores":[ ... ] }
→ 404 { ... }
```


## 3.5 `PATCH /api/objetivo_desarrollo_sostenible/{id}` — actualizar

```json
{
  "categoria": "Ambiental"
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Objetivo de desarrollo sostenible actualizado.",
    "filasAfectadas":1
}

→ 400 { ..., "detalle":"No se envió ningún campo para actualizar." }
→ 404 { ... }
```


## 3.6 `DELETE /api/objetivo_desarrollo_sostenible/{id}` — retirar

```text
→ 200 {
    "estado":200,
    "mensaje":"Objetivo de desarrollo sostenible retirado.",
    "filasAfectadas":1
}

→ 404 { ... }
```


---

# 4. `area_aplicacion`

## 4.1 `GET /api/area_aplicacion` — listar

```text
→ 200 {
    "recurso":"area_aplicacion",
    "total":21,
    "datos":[ ... ]
}

→ 204 sin cuerpo
```

Solo devuelve registros activos.


## 4.2 `GET /api/area_aplicacion/{id}` — obtener una

```text
→ 200 {
    "id":1,
    "nombre":"Agricultura, ganadería, caza, silvicultura y pesca"
}

→ 404 {
    "estado":404,
    "mensaje":"Área de aplicación no encontrada.",
    "detalle":"No existe un área de aplicación activa con el id solicitado."
}
```


## 4.3 `POST /api/area_aplicacion` — crear

```json
{
  "id": 22,
  "nombre": "Área de aplicación de ejemplo"
}
```

```text
→ 201 {
    "estado":201,
    "mensaje":"Área de aplicación creada exitosamente.",
    "datos":{ ... }
}

→ 422 { ..., "errores":[ ... ] }

→ 409 {
    "estado":409,
    "mensaje":"Conflicto de datos.",
    "detalle":"Ya existe un área de aplicación con esa llave."
}
```


## 4.4 `PUT /api/area_aplicacion/{id}` — reemplazar

```json
{
  "nombre": "Área de aplicación actualizada"
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Área de aplicación reemplazada.",
    "filasAfectadas":1
}

→ 422 { ..., "errores":[ ... ] }
→ 404 { ... }
```


## 4.5 `PATCH /api/area_aplicacion/{id}` — actualizar

```json
{
  "nombre": "Nuevo nombre"
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Área de aplicación actualizada.",
    "filasAfectadas":1
}

→ 400 { ..., "detalle":"No se envió ningún campo para actualizar." }
→ 404 { ... }
```


## 4.6 `DELETE /api/area_aplicacion/{id}` — retirar

```text
→ 200 {
    "estado":200,
    "mensaje":"Área de aplicación retirada.",
    "filasAfectadas":1
}

→ 404 { ... }
```


---

# 5. `termino_clave`

## 5.1 `GET /api/termino_clave` — listar

```text
→ 200 {
    "recurso":"termino_clave",
    "total":2,
    "datos":[ ... ]
}

→ 204 sin cuerpo
```

`termino_clave` puede iniciar vacío. Un 204 no significa que la API esté
fallando.


## 5.2 `GET /api/termino_clave/{termino}` — obtener uno

La llave de este recurso es `termino`, no un campo `id`.

```text
→ 200 {
    "termino":"inteligencia artificial",
    "termino_ingles":"artificial intelligence"
}

→ 404 {
    "estado":404,
    "mensaje":"Término clave no encontrado.",
    "detalle":"No existe un término clave activo con la llave solicitada."
}
```


## 5.3 `POST /api/termino_clave` — crear

`termino` es obligatorio y funciona como llave primaria.

`termino_ingles` es opcional.

```json
{
  "termino": "inteligencia artificial",
  "termino_ingles": "artificial intelligence"
}
```

También es válido:

```json
{
  "termino": "inteligencia artificial"
}
```

```text
→ 201 {
    "estado":201,
    "mensaje":"Término clave creado exitosamente.",
    "datos":{ ... }
}

→ 422 { ..., "errores":[ ... ] }

→ 409 {
    "estado":409,
    "mensaje":"Conflicto de datos.",
    "detalle":"Ya existe un término clave con esa llave."
}
```


## 5.4 `PUT /api/termino_clave/{termino}` — reemplazar

La llave `termino` va en la ruta y no puede modificarse.

Para que PUT represente un reemplazo completo, `termino_ingles` debe aparecer
en el cuerpo, aunque su valor puede ser `null`.

```json
{
  "termino_ingles": "artificial intelligence"
}
```

También es válido:

```json
{
  "termino_ingles": null
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Término clave reemplazado.",
    "filasAfectadas":1
}

→ 422 { ..., "errores":[ ... ] }
→ 404 { ... }
```


## 5.5 `PATCH /api/termino_clave/{termino}` — actualizar

```json
{
  "termino_ingles": "artificial intelligence"
}
```

PATCH también puede utilizar `null` para retirar una traducción existente:

```json
{
  "termino_ingles": null
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Término clave actualizado.",
    "filasAfectadas":1
}

→ 400 { ..., "detalle":"No se envió ningún campo para actualizar." }
→ 404 { ... }
```


## 5.6 `DELETE /api/termino_clave/{termino}` — retirar

```text
→ 200 {
    "estado":200,
    "mensaje":"Término clave retirado.",
    "filasAfectadas":1
}

→ 404 { ... }
```

La fila permanece en MariaDB con `activo = FALSE`. Su llave continúa ocupada.


---

# 6. `universidad`

## 6.1 `GET /api/universidad` — listar

```text
→ 200 {
    "recurso":"universidad",
    "total":6,
    "datos":[ ... ]
}

→ 204 sin cuerpo
```

Solo devuelve universidades activas.


## 6.2 `GET /api/universidad/{id}` — obtener una

```text
→ 200 {
    "id":1,
    "nombre":"Universidad de ejemplo",
    "tipo":"Pública",
    "ciudad":"Medellín"
}

→ 404 {
    "estado":404,
    "mensaje":"Universidad no encontrada.",
    "detalle":"No existe una universidad activa con el id solicitado."
}
```


## 6.3 `POST /api/universidad` — crear

```json
{
  "id": 7,
  "nombre": "Universidad de ejemplo",
  "tipo": "Pública",
  "ciudad": "Medellín"
}
```

```text
→ 201 {
    "estado":201,
    "mensaje":"Universidad creada exitosamente.",
    "datos":{ ... }
}

→ 422 { ..., "errores":[ ... ] }

→ 409 {
    "estado":409,
    "mensaje":"Conflicto de datos.",
    "detalle":"Ya existe una universidad con esa llave."
}
```


## 6.4 `PUT /api/universidad/{id}` — reemplazar

```json
{
  "nombre": "Universidad actualizada",
  "tipo": "Privada",
  "ciudad": "Bogotá"
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Universidad reemplazada.",
    "filasAfectadas":1
}

→ 422 { ..., "errores":[ ... ] }
→ 404 { ... }
```


## 6.5 `PATCH /api/universidad/{id}` — actualizar

```json
{
  "ciudad": "Bogotá"
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Universidad actualizada.",
    "filasAfectadas":1
}

→ 400 { ..., "detalle":"No se envió ningún campo para actualizar." }
→ 404 { ... }
```


## 6.6 `DELETE /api/universidad/{id}` — retirar

```text
→ 200 {
    "estado":200,
    "mensaje":"Universidad retirada.",
    "filasAfectadas":1
}

→ 404 { ... }
```


---

# 7. `linea_investigacion`

## 7.1 `GET /api/linea_investigacion` — listar

```text
→ 200 {
    "recurso":"linea_investigacion",
    "total":2,
    "datos":[ ... ]
}

→ 204 sin cuerpo
```

`linea_investigacion` puede iniciar sin registros.


## 7.2 `GET /api/linea_investigacion/{id}` — obtener una

```text
→ 200 {
    "id":1,
    "nombre":"Ingeniería de software",
    "descripcion":"Línea de investigación de ejemplo"
}

→ 404 {
    "estado":404,
    "mensaje":"Línea de investigación no encontrada.",
    "detalle":"No existe una línea de investigación activa con el id solicitado."
}
```


## 7.3 `POST /api/linea_investigacion` — crear

El `id` **no se envía**. MariaDB lo genera mediante `AUTO_INCREMENT`.

```json
{
  "nombre": "Ingeniería de software",
  "descripcion": "Línea de investigación de ejemplo"
}
```

```text
→ 201 {
    "estado":201,
    "mensaje":"Línea de investigación creada exitosamente.",
    "datos":{
        "id":1,
        "nombre":"Ingeniería de software",
        "descripcion":"Línea de investigación de ejemplo"
    }
}

→ 422 { ..., "errores":[ ... ] }
```

El `id` generado se devuelve dentro de `datos` para que quien consume la API
conozca la llave asignada a la nueva fila.

Si el cliente intenta enviar `id` o `activo` como campos de creación, el
cuerpo se considera inválido y responde 422.


## 7.4 `PUT /api/linea_investigacion/{id}` — reemplazar

```json
{
  "nombre": "Ingeniería de software",
  "descripcion": "Descripción actualizada"
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Línea de investigación reemplazada.",
    "filasAfectadas":1
}

→ 422 { ..., "errores":[ ... ] }
→ 404 { ... }
```


## 7.5 `PATCH /api/linea_investigacion/{id}` — actualizar

```json
{
  "descripcion": "Nueva descripción"
}
```

```text
→ 200 {
    "estado":200,
    "mensaje":"Línea de investigación actualizada.",
    "filasAfectadas":1
}

→ 400 { ..., "detalle":"No se envió ningún campo para actualizar." }
→ 404 { ... }
```


## 7.6 `DELETE /api/linea_investigacion/{id}` — retirar

```text
→ 200 {
    "estado":200,
    "mensaje":"Línea de investigación retirada.",
    "filasAfectadas":1
}

→ 404 { ... }
```

El borrado es lógico y no reinicia ni reutiliza automáticamente el valor del
`AUTO_INCREMENT`.


---

## Cómo traduce el front estos desenlaces

| Lo que responde la API | Lo que ve el usuario |
|---|---|
| `200` en un listado o lectura | Los datos solicitados |
| `204` | «Todavía no hay registros» y la opción de agregar — **no es un error** |
| `201` en POST | Un aviso de que el registro fue agregado correctamente |
| `200` en PUT o PATCH | Un aviso de que los cambios fueron guardados |
| `200` en DELETE | Un aviso de que el registro fue retirado |
| `400` | La explicación de por qué la operación no puede realizarse, sin mostrar el número |
| `404` | Un aviso indicando que el registro no está disponible |
| `409` | Un aviso indicando que ya existe un registro con esa llave |
| Un cuerpo inválido con `errores[]` | Un aviso por cada error con el texto enviado por la API |
| `500` | Un aviso general de que ocurrió un problema en el servicio |
| **La API no responde** | «El servicio no está disponible» y la pantalla **sigue en pie** |

Ningún código de estado, verbo HTTP ni ruta interna de la API se muestra como
instrucción para la persona que utiliza el frontend.
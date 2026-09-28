# Tareas — v1: Investigación (PHP + MariaDB)

El orden importa: **de adentro hacia afuera**. Cada fase termina con algo que
se puede comprobar, no con «ya quedó».

La v1 se completa solamente cuando los seis recursos funcionan de extremo a
extremo, tanto en la API como en el frontend.


## Fase 0 — El compose y la base

- [ ] Definir `docker-compose.yml` con los servicios necesarios para MariaDB,
      la API, el frontend y phpMyAdmin.
- [ ] Definir y probar los puertos reales de la API, el frontend y phpMyAdmin.
- [ ] Configurar las variables de entorno de MariaDB y la URL de la API.
- [ ] Mantener las credenciales reales fuera de Git.
- [ ] Mantener `.env` ignorado y `.env.example` versionado.
- [ ] `db/init.sql` derivado del script del curso, con los cambios documentados
      en su cabecera.
- [ ] Verificar que `activo` está disponible para los seis recursos de la v1.
- [ ] Agregar al `init.sql` los 6 registros de `universidad` provenientes de la
      fuente de datos entregada.
- [ ] El frontend queda **sin** credenciales de MariaDB y sin dependencia
      directa de la base de datos.

**Verificación:**

Levantar primero la base y comprobar que las semillas esperadas sean:

```text
area_conocimiento                 218
objetivo_desarrollo_sostenible     17
area_aplicacion                    21
universidad                         6
```

`termino_clave` y `linea_investigacion` pueden iniciar vacías.

Los comandos exactos, nombres de servicios y puertos se escribirán en
`7_quickstart.md` después de que el `docker-compose.yml` haya sido probado.


## Fase 1 — Los modelos

- [ ] `modelos/AreaConocimiento.php`
- [ ] `modelos/ObjetivoDesarrolloSostenible.php`
- [ ] `modelos/AreaAplicacion.php`
- [ ] `modelos/TerminoClave.php`
- [ ] `modelos/Universidad.php`
- [ ] `modelos/LineaInvestigacion.php`
- [ ] Propiedades privadas y métodos de acceso según corresponda.
- [ ] Las llaves suministradas por quien crea el registro no tienen setter para
      ser modificadas después de la creación.
- [ ] `linea_investigacion.id` representa la llave generada por MariaDB.
- [ ] **Sin** `activo` como campo editable de la ficha
      (`5_data_model.md`, §4).

**Verificación:** cada modelo puede construirse con los datos correspondientes
a su tabla y convertirse a una estructura utilizable por la API sin exponer
`activo` como dato editable.


## Fase 2 — Las interfaces

- [ ] `repositorios/IRepositorioAreaConocimiento.php`
- [ ] `repositorios/IRepositorioObjetivoDesarrolloSostenible.php`
- [ ] `repositorios/IRepositorioAreaAplicacion.php`
- [ ] `repositorios/IRepositorioTerminoClave.php`
- [ ] `repositorios/IRepositorioUniversidad.php`
- [ ] `repositorios/IRepositorioLineaInvestigacion.php`

- [ ] `servicios/IServicioAreaConocimiento.php`
- [ ] `servicios/IServicioObjetivoDesarrolloSostenible.php`
- [ ] `servicios/IServicioAreaAplicacion.php`
- [ ] `servicios/IServicioTerminoClave.php`
- [ ] `servicios/IServicioUniversidad.php`
- [ ] `servicios/IServicioLineaInvestigacion.php`

Las interfaces deben expresar las operaciones necesarias para:

```text
listar
obtener por llave
crear
reemplazar
actualizar parcialmente
retirar
```

**Verificación:** las pruebas de capas ya pueden empezar a escribirse contra
las interfaces aunque todavía no exista la implementación completa.


## Fase 3 — Los repositorios MariaDB

- [ ] `RepositorioAreaConocimientoMariaDB.php`
- [ ] `RepositorioObjetivoDesarrolloSostenibleMariaDB.php`
- [ ] `RepositorioAreaAplicacionMariaDB.php`
- [ ] `RepositorioTerminoClaveMariaDB.php`
- [ ] `RepositorioUniversidadMariaDB.php`
- [ ] `RepositorioLineaInvestigacionMariaDB.php`
- [ ] Todos utilizan PDO y prepared statements.
- [ ] Todas las consultas normales filtran por `activo = TRUE`.
- [ ] El borrado lógico se hace mediante
      `UPDATE ... SET activo = FALSE`.
- [ ] El retiro incluye `AND activo = TRUE`.
- [ ] Configurar `PDO::MYSQL_ATTR_FOUND_ROWS => true` para evitar 404 falsos en
      UPDATE con los mismos valores.
- [ ] Traducir una llave duplicada a `ConflictoExcepcion`.
- [ ] `termino_clave` utiliza `termino` como llave.
- [ ] `linea_investigacion` obtiene el `id` generado mediante
      `AUTO_INCREMENT`.

**Verificación:** desde PHP se pueden listar los datos de cada recurso y se
obtienen solamente filas activas.

Los conteos iniciales deben coincidir con los definidos en
`5_data_model.md`.


## Fase 4 — Los servicios

- [ ] `ServicioAreaConocimiento.php`
- [ ] `ServicioObjetivoDesarrolloSostenible.php`
- [ ] `ServicioAreaAplicacion.php`
- [ ] `ServicioTerminoClave.php`
- [ ] `ServicioUniversidad.php`
- [ ] `ServicioLineaInvestigacion.php`
- [ ] Los servicios dependen de interfaces de repositorio.
- [ ] Lanzan `NoEncontradoExcepcion` cuando una ficha no existe o está retirada.
- [ ] Un PATCH vacío produce `InvalidArgumentException`.
- [ ] Los servicios **nunca** devuelven códigos HTTP.
- [ ] `servicios/ensamblador.php` es el único punto que construye las
      implementaciones concretas.

**Verificación:** cada servicio puede ejecutarse con un repositorio falso en
memoria y sus reglas funcionan sin MariaDB.


## Fase 5 — Los controladores

- [ ] `ControladorAreaConocimiento.php`
- [ ] `ControladorObjetivoDesarrolloSostenible.php`
- [ ] `ControladorAreaAplicacion.php`
- [ ] `ControladorTerminoClave.php`
- [ ] `ControladorUniversidad.php`
- [ ] `ControladorLineaInvestigacion.php`
- [ ] Validar campos obligatorios, tipos y longitudes según `2_spec.md`.
- [ ] Mantener una lista blanca de campos permitidos.
- [ ] Rechazar `activo` como campo de POST, PUT o PATCH.
- [ ] Rechazar las llaves primarias en PUT y PATCH.
- [ ] En `linea_investigacion`, rechazar también `id` en POST.
- [ ] Utilizar la misma lógica de validación para PUT y PATCH, diferenciando
      qué campos son obligatorios.
- [ ] Traducir las excepciones a los códigos definidos en `6_contracts.md`.

La traducción esperada es:

```text
cuerpo inválido                    → 422
operación inválida                 → 400
recurso inexistente o retirado     → 404
llave duplicada                    → 409
error inesperado                   → 500
```

**Verificación:** enviar cuerpos válidos e inválidos directamente a cada
controlador y comprobar que el servicio solo se ejecuta cuando la forma de la
petición es válida.


## Fase 6 — El enrutador

- [ ] `index.php` reconoce las rutas específicas de los seis recursos.
- [ ] No existe una ruta genérica como `/api/{tabla}`.
- [ ] Implementar `GET /` como diagnóstico de la API.
- [ ] Registrar para cada recurso:

```text
GET    /api/recurso
GET    /api/recurso/{llave}
POST   /api/recurso
PUT    /api/recurso/{llave}
PATCH  /api/recurso/{llave}
DELETE /api/recurso/{llave}
```

- [ ] `termino_clave` utiliza `{termino}` como llave en la ruta.
- [ ] Los demás recursos individuales utilizan `{id}`.
- [ ] Una ruta existente con un método no permitido responde 405.
- [ ] El 404 de **ruta inexistente** se diferencia del 404 de
      **registro inexistente**.

**Verificación:** ejecutar manualmente los contratos definidos en
`6_contracts.md`.

Cuando los puertos y el compose estén confirmados, estos mismos pasos se
convertirán en los comandos ejecutables de `7_quickstart.md`.


## Fase 7 — Las pruebas de capas

- [ ] `pruebas/prueba_capas.php`.
- [ ] Crear repositorios falsos en memoria para los seis servicios.
- [ ] Los repositorios falsos también realizan borrado lógico.
- [ ] Comprobar búsqueda de registros activos.
- [ ] Comprobar recurso inexistente.
- [ ] Comprobar recurso retirado.
- [ ] Comprobar PUT completo.
- [ ] Comprobar PATCH parcial.
- [ ] Comprobar PATCH vacío.
- [ ] Comprobar que retirar dos veces produce el comportamiento de
      `NoEncontradoExcepcion`.
- [ ] Comprobar que las reglas funcionan **con MariaDB apagada**.

**Verificación:**

```text
php pruebas/prueba_capas.php
```

Todas las verificaciones deben terminar en `[OK]`.

El comando Docker exacto se añadirá al `7_quickstart.md` cuando el nombre real
del servicio API esté definido.


## Fase 8 — LA PANTALLA (la otra mitad de la versión)

En el repositorio del frontend:

- [ ] `cliente_api.php`: el único componente que habla con la API mediante HTTP.
- [ ] **Cero PDO** en el frontend.
- [ ] **Cero credenciales de MariaDB**.
- [ ] `index.php` enruta las pantallas.
- [ ] Permitir servir directamente los archivos estáticos cuando se utilice
      `php -S`.
- [ ] `vistas/` contiene la plantilla, inicio, listados, formularios y 404.
- [ ] Bootstrap está descargado dentro de `publico/`, no mediante CDN.
- [ ] Pantalla para `area_conocimiento`.
- [ ] Pantalla para `objetivo_desarrollo_sostenible`.
- [ ] Pantalla para `area_aplicacion`.
- [ ] Pantalla para `termino_clave`.
- [ ] Pantalla para `universidad`.
- [ ] Pantalla para `linea_investigacion`.
- [ ] Cada recurso permite listar, crear, editar y retirar.
- [ ] El frontend diferencia guardar la ficha completa de guardar cambios
      parciales.
- [ ] Un listado 204 muestra «Todavía no hay registros» y no un error.
- [ ] Un 409 muestra un mensaje comprensible de llave ocupada.
- [ ] Si la API está apagada, la pantalla continúa respondiendo y no muestra
      datos de MariaDB.

**Verificación:** recorrer manualmente los seis recursos desde el navegador.

Después se preparará el guion de humo del frontend y su comando definitivo se
documentará en `7_quickstart.md`.


## Fase 9 — Completar el quickstart

- [ ] Confirmar los nombres reales de los servicios Docker.
- [ ] Confirmar los puertos reales de API, frontend y phpMyAdmin.
- [ ] Completar `7_quickstart.md` con comandos que hayan sido ejecutados de
      verdad.
- [ ] Comprobar los conteos iniciales.
- [ ] Comprobar POST → 201.
- [ ] Comprobar PUT y PATCH.
- [ ] Comprobar DELETE lógico.
- [ ] Comprobar segundo DELETE → 404.
- [ ] Comprobar llave duplicada → 409.
- [ ] Comprobar validaciones → 422.
- [ ] Comprobar PATCH vacío → 400.
- [ ] Comprobar prueba de capas sin MariaDB.
- [ ] Comprobar frontend con la API apagada.

**Verificación:** otra persona puede seguir `7_quickstart.md` desde cero sin
tener que adivinar puertos, nombres de servicios ni comandos.


## Fase 10 — Cerrar

- [ ] Preparar la colección de Postman con los seis recursos y los casos
      principales definidos en `6_contracts.md`.
- [ ] Ejecutar todos los criterios de aceptación de `2_spec.md`.
- [ ] Confirmar que los 6 registros iniciales de `universidad` están cargados.
- [ ] Confirmar que `termino_clave` y `linea_investigacion` pueden iniciar
      vacías sin romper el frontend.
- [ ] Completar `9_checklist.md`.
- [ ] Revisar el checklist manualmente: que una prueba responda no garantiza
      que la interfaz sea comprensible.
- [ ] Confirmar que no quedaron secretos versionados.
- [ ] Confirmar que la v1 no implementó funcionalidad de versiones posteriores.
- [ ] Integrar los cambios mediante las ramas y Pull Requests definidos para
      el proyecto.
- [ ] Crear el tag `v1` solamente después de que todos los criterios estén
      aprobados.
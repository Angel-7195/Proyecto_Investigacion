# Asignación de responsabilidades — v1 Investigación

La versión 1 comprende los seis recursos sin claves foráneas del módulo de
Investigación.

La implementación se divide entre los integrantes del equipo para permitir
trabajo independiente mediante ramas y Pull Requests.

## Distribución de recursos

| Responsable | Recurso |
|---|---|
| Ángel David Gutiérrez Ladino | `termino_clave` |
| Ángel David Gutiérrez Ladino | `universidad` |
| Ángel David Gutiérrez Ladino | `linea_investigacion` |
| Miguel Angel Lotero Alvarez | `area_conocimiento` |
| Miguel Angel Lotero Alvarez | `objetivo_desarrollo_sostenible` |
| Miguel Angel Lotero Alvarez | `area_aplicacion` |

## Alcance de la responsabilidad

Cada responsable implementa para sus recursos:

- modelo;
- interfaz del repositorio;
- repositorio MariaDB;
- interfaz del servicio;
- servicio;
- controlador;
- pruebas correspondientes;
- integración del recurso con el frontend.

La implementación debe respetar `1_constitution.md`, `2_spec.md`,
`3_plan.md`, `5_data_model.md` y `6_contracts.md`.

## Archivos compartidos

Para reducir conflictos entre ramas, los archivos que integran varios recursos
serán administrados principalmente por **Ángel David Gutiérrez Ladino**:

- `docker-compose.yml`
- `api_investigacion/index.php`
- `api_investigacion/servicios/ensamblador.php`
- documentación del Spec Kit
- archivos generales de configuración

Cuando un recurso desarrollado por otro integrante necesite integrarse en uno
de estos archivos, el cambio se incorporará de manera coordinada mediante
Pull Request.

## Flujo de Git

- Cada integrante trabaja en su propia rama.
- No se realizan `push` directos a `main`.
- Todo cambio que llegue a `main` debe hacerlo mediante Pull Request.
- Cada Pull Request debe contener cambios relacionados con la responsabilidad
  asignada al integrante.
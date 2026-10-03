<?php

declare(strict_types=1);

// ================================================================
// AREA DE CONOCIMIENTO
// ================================================================

require_once __DIR__ . '/../modelos/AreaConocimiento.php';
require_once __DIR__ . '/../repositorios/IRepositorioAreaConocimiento.php';
require_once __DIR__ . '/../repositorios/RepositorioAreaConocimientoMariaDB.php';
require_once __DIR__ . '/IServicioAreaConocimiento.php';
require_once __DIR__ . '/ServicioAreaConocimiento.php';
require_once __DIR__ . '/../controladores/ControladorAreaConocimiento.php';

// ================================================================
// OBJETIVO DE DESARROLLO SOSTENIBLE
// ================================================================

require_once __DIR__ . '/../modelos/ObjetivoDesarrolloSostenible.php';
require_once __DIR__ . '/../repositorios/IRepositorioObjetivoDesarrolloSostenible.php';
require_once __DIR__ . '/../repositorios/RepositorioObjetivoDesarrolloSostenibleMariaDB.php';
require_once __DIR__ . '/IServicioObjetivoDesarrolloSostenible.php';
require_once __DIR__ . '/ServicioObjetivoDesarrolloSostenible.php';
require_once __DIR__ . '/../controladores/ControladorObjetivoDesarrolloSostenible.php';

// ================================================================
// AREA DE APLICACION
// ================================================================

require_once __DIR__ . '/../modelos/AreaAplicacion.php';
require_once __DIR__ . '/../repositorios/IRepositorioAreaAplicacion.php';
require_once __DIR__ . '/../repositorios/RepositorioAreaAplicacionMariaDB.php';
require_once __DIR__ . '/IServicioAreaAplicacion.php';
require_once __DIR__ . '/ServicioAreaAplicacion.php';
require_once __DIR__ . '/../controladores/ControladorAreaAplicacion.php';

// ================================================================
// RECURSOS DE ANGEL
// ================================================================

require_once __DIR__ . '/../repositorios/RepositorioTerminoClaveMariaDB.php';
require_once __DIR__ . '/ServicioTerminoClave.php';
require_once __DIR__ . '/../controladores/ControladorTerminoClave.php';

require_once __DIR__ . '/../repositorios/RepositorioUniversidadMariaDB.php';
require_once __DIR__ . '/ServicioUniversidad.php';
require_once __DIR__ . '/../controladores/ControladorUniversidad.php';

require_once __DIR__ . '/../repositorios/RepositorioLineaInvestigacionMariaDB.php';
require_once __DIR__ . '/ServicioLineaInvestigacion.php';
require_once __DIR__ . '/../controladores/ControladorLineaInvestigacion.php';

/**
 * Construye la conexión PDO utilizando las variables de entorno.
 */
function crearConexion(): PDO
{
    $dsn = getenv('DB_DSN');
    $usuario = getenv('DB_USUARIO');
    $clave = getenv('DB_CLAVE');

    if (
        $dsn === false
        || $usuario === false
        || $clave === false
    ) {
        throw new RuntimeException(
            'Falta la configuración de conexión a la base de datos.'
        );
    }

    return new PDO(
        $dsn,
        $usuario,
        $clave,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_FOUND_ROWS => true,
        ]
    );
}

/**
 * Construye todos los controladores de la API.
 */
function ensamblarControladores(): array
{
    $pdo = crearConexion();

    // AREA DE CONOCIMIENTO
    $repositorioAreaConocimiento =
        new RepositorioAreaConocimientoMariaDB($pdo);

    $servicioAreaConocimiento =
        new ServicioAreaConocimiento($repositorioAreaConocimiento);

    $controladorAreaConocimiento =
        new ControladorAreaConocimiento($servicioAreaConocimiento);

    // OBJETIVO DE DESARROLLO SOSTENIBLE
    $repositorioObjetivo =
        new RepositorioObjetivoDesarrolloSostenibleMariaDB($pdo);

    $servicioObjetivo =
        new ServicioObjetivoDesarrolloSostenible($repositorioObjetivo);

    $controladorObjetivo =
        new ControladorObjetivoDesarrolloSostenible($servicioObjetivo);

    // AREA DE APLICACION
    $repositorioAreaAplicacion =
        new RepositorioAreaAplicacionMariaDB($pdo);

    $servicioAreaAplicacion =
        new ServicioAreaAplicacion($repositorioAreaAplicacion);

    $controladorAreaAplicacion =
        new ControladorAreaAplicacion($servicioAreaAplicacion);

    // TERMINO CLAVE
    $repositorioTerminoClave =
        new RepositorioTerminoClaveMariaDB($pdo);

    $servicioTerminoClave =
        new ServicioTerminoClave($repositorioTerminoClave);

    $controladorTerminoClave =
        new ControladorTerminoClave($servicioTerminoClave);

    // UNIVERSIDAD
    $repositorioUniversidad =
        new RepositorioUniversidadMariaDB($pdo);

    $servicioUniversidad =
        new ServicioUniversidad($repositorioUniversidad);

    $controladorUniversidad =
        new ControladorUniversidad($servicioUniversidad);

    // LINEA DE INVESTIGACION
    $repositorioLineaInvestigacion =
        new RepositorioLineaInvestigacionMariaDB($pdo);

    $servicioLineaInvestigacion =
        new ServicioLineaInvestigacion($repositorioLineaInvestigacion);

    $controladorLineaInvestigacion =
        new ControladorLineaInvestigacion($servicioLineaInvestigacion);

    // CONTROLADORES DISPONIBLES
    return [
        'area_conocimiento' => $controladorAreaConocimiento,
        'objetivo_desarrollo_sostenible' => $controladorObjetivo,
        'area_aplicacion' => $controladorAreaAplicacion,
        'termino_clave' => $controladorTerminoClave,
        'universidad' => $controladorUniversidad,
        'linea_investigacion' => $controladorLineaInvestigacion,
    ];
}

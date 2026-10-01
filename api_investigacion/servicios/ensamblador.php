<?php

declare(strict_types=1);

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
 * Construye la conexión PDO utilizando únicamente
 * las variables de entorno configuradas para la API.
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
 * Construye las implementaciones concretas utilizadas por la API.
 */
function ensamblarControladores(): array
{
    $pdo = crearConexion();

    // --------------------------------------------------------------
    // TERMINO CLAVE
    // --------------------------------------------------------------

    $repositorioTerminoClave =
        new RepositorioTerminoClaveMariaDB($pdo);

    $servicioTerminoClave =
        new ServicioTerminoClave($repositorioTerminoClave);

    $controladorTerminoClave =
        new ControladorTerminoClave($servicioTerminoClave);


    // --------------------------------------------------------------
    // UNIVERSIDAD
    // --------------------------------------------------------------

    $repositorioUniversidad =
        new RepositorioUniversidadMariaDB($pdo);

    $servicioUniversidad =
        new ServicioUniversidad($repositorioUniversidad);

    $controladorUniversidad =
        new ControladorUniversidad($servicioUniversidad);


    // --------------------------------------------------------------
    // LINEA DE INVESTIGACION
    // --------------------------------------------------------------

    $repositorioLineaInvestigacion =
        new RepositorioLineaInvestigacionMariaDB($pdo);

    $servicioLineaInvestigacion =
        new ServicioLineaInvestigacion(
            $repositorioLineaInvestigacion
        );

    $controladorLineaInvestigacion =
        new ControladorLineaInvestigacion(
            $servicioLineaInvestigacion
        );


    // --------------------------------------------------------------
    // CONTROLADORES DISPONIBLES
    // --------------------------------------------------------------

    return [
        'termino_clave' => $controladorTerminoClave,
        'universidad' => $controladorUniversidad,
        'linea_investigacion' => $controladorLineaInvestigacion,
    ];
}
<?php

declare(strict_types=1);

require_once __DIR__ . '/../repositorios/RepositorioTerminoClaveMariaDB.php';
require_once __DIR__ . '/ServicioTerminoClave.php';
require_once __DIR__ . '/../controladores/ControladorTerminoClave.php';

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
            PDO::MYSQL_ATTR_FOUND_ROWS => true,
        ]
    );
}

/**
 * Construye las implementaciones concretas utilizadas por la API.
 *
 * Los demás recursos de la v1 se agregarán aquí cuando
 * sus respectivas capas estén implementadas.
 */
function ensamblarControladores(): array
{
    $pdo = crearConexion();

    $repositorioTerminoClave =
        new RepositorioTerminoClaveMariaDB($pdo);

    $servicioTerminoClave =
        new ServicioTerminoClave($repositorioTerminoClave);

    $controladorTerminoClave =
        new ControladorTerminoClave($servicioTerminoClave);

    return [
        'termino_clave' => $controladorTerminoClave,
    ];
}
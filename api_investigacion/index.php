<?php

/**
 * index.php — Front Controller de la API de Investigación v1.
 *
 * Todas las peticiones HTTP de la API entran por este archivo.
 * Aquí solamente se decide qué controlador debe atender cada ruta.
 *
 * La v1 trabaja con:
 * - area_conocimiento
 * - objetivo_desarrollo_sostenible
 * - area_aplicacion
 * - termino_clave
 * - universidad
 * - linea_investigacion
 *
 * Este archivo NO contiene SQL ni reglas de negocio.
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

// ----------------------------------------------------------------------
// 1. Capturar la petición
// ----------------------------------------------------------------------

$metodo = $_SERVER['REQUEST_METHOD'];
$ruta = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';

$contenido = file_get_contents('php://input');
$cuerpo = $contenido !== ''
    ? json_decode($contenido, true)
    : [];

// ----------------------------------------------------------------------
// 2. Diagnóstico
// ----------------------------------------------------------------------

if ($ruta === '/' && $metodo === 'GET') {
    echo json_encode([
        'mensaje' => 'API de Investigación funcionando',
        'version' => 'v1',
        'recursos' => [
            'area_conocimiento',
            'objetivo_desarrollo_sostenible',
            'area_aplicacion',
            'termino_clave',
            'universidad',
            'linea_investigacion',
        ],
    ], JSON_UNESCAPED_UNICODE);

    return;
}

// ======================================================================
// AREA DE CONOCIMIENTO
// ======================================================================

if ($ruta === '/api/area_conocimiento') {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorAreaConocimiento.php';

    $controlador = new ControladorAreaConocimiento(
        crearServicioAreaConocimiento()
    );

    if ($metodo === 'GET') {
        $controlador->listar();
    } elseif ($metodo === 'POST') {
        $controlador->crear($cuerpo);
    } else {
        responderNoPermitido();
    }

    return;
}

if (preg_match('#^/api/area_conocimiento/([^/]+)$#', $ruta, $coincidencias)) {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorAreaConocimiento.php';

    $controlador = new ControladorAreaConocimiento(
        crearServicioAreaConocimiento()
    );

    $clave = urldecode($coincidencias[1]);

    enrutarFicha($controlador, $metodo, $clave, $cuerpo);
    return;
}

// ======================================================================
// OBJETIVO DE DESARROLLO SOSTENIBLE
// ======================================================================

if ($ruta === '/api/objetivo_desarrollo_sostenible') {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorObjetivoDesarrolloSostenible.php';

    $controlador = new ControladorObjetivoDesarrolloSostenible(
        crearServicioObjetivoDesarrolloSostenible()
    );

    if ($metodo === 'GET') {
        $controlador->listar();
    } elseif ($metodo === 'POST') {
        $controlador->crear($cuerpo);
    } else {
        responderNoPermitido();
    }

    return;
}

if (preg_match('#^/api/objetivo_desarrollo_sostenible/([^/]+)$#', $ruta, $coincidencias)) {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorObjetivoDesarrolloSostenible.php';

    $controlador = new ControladorObjetivoDesarrolloSostenible(
        crearServicioObjetivoDesarrolloSostenible()
    );

    $clave = (int) urldecode($coincidencias[1]);

    enrutarFicha($controlador, $metodo, $clave, $cuerpo);
    return;
}

// ======================================================================
// AREA DE APLICACION
// ======================================================================

if ($ruta === '/api/area_aplicacion') {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorAreaAplicacion.php';

    $controlador = new ControladorAreaAplicacion(
        crearServicioAreaAplicacion()
    );

    if ($metodo === 'GET') {
        $controlador->listar();
    } elseif ($metodo === 'POST') {
        $controlador->crear($cuerpo);
    } else {
        responderNoPermitido();
    }

    return;
}

if (preg_match('#^/api/area_aplicacion/([^/]+)$#', $ruta, $coincidencias)) {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorAreaAplicacion.php';

    $controlador = new ControladorAreaAplicacion(
        crearServicioAreaAplicacion()
    );

    $clave = (int) urldecode($coincidencias[1]);

    enrutarFicha($controlador, $metodo, $clave, $cuerpo);
    return;
}

// ======================================================================
// TERMINO CLAVE
// ======================================================================

if ($ruta === '/api/termino_clave') {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorTerminoClave.php';

    $controlador = new ControladorTerminoClave(
        crearServicioTerminoClave()
    );

    if ($metodo === 'GET') {
        $controlador->listar();
    } elseif ($metodo === 'POST') {
        $controlador->crear($cuerpo);
    } else {
        responderNoPermitido();
    }

    return;
}

if (preg_match('#^/api/termino_clave/([^/]+)$#', $ruta, $coincidencias)) {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorTerminoClave.php';

    $controlador = new ControladorTerminoClave(
        crearServicioTerminoClave()
    );

    $clave = urldecode($coincidencias[1]);

    enrutarFicha($controlador, $metodo, $clave, $cuerpo);
    return;
}

// ======================================================================
// UNIVERSIDAD
// ======================================================================

if ($ruta === '/api/universidad') {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorUniversidad.php';

    $controlador = new ControladorUniversidad(
        crearServicioUniversidad()
    );

    if ($metodo === 'GET') {
        $controlador->listar();
    } elseif ($metodo === 'POST') {
        $controlador->crear($cuerpo);
    } else {
        responderNoPermitido();
    }

    return;
}

if (preg_match('#^/api/universidad/([^/]+)$#', $ruta, $coincidencias)) {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorUniversidad.php';

    $controlador = new ControladorUniversidad(
        crearServicioUniversidad()
    );

    $clave = (int) urldecode($coincidencias[1]);

    enrutarFicha($controlador, $metodo, $clave, $cuerpo);
    return;
}

// ======================================================================
// LINEA DE INVESTIGACION
// ======================================================================

if ($ruta === '/api/linea_investigacion') {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorLineaInvestigacion.php';

    $controlador = new ControladorLineaInvestigacion(
        crearServicioLineaInvestigacion()
    );

    if ($metodo === 'GET') {
        $controlador->listar();
    } elseif ($metodo === 'POST') {
        $controlador->crear($cuerpo);
    } else {
        responderNoPermitido();
    }

    return;
}

if (preg_match('#^/api/linea_investigacion/([^/]+)$#', $ruta, $coincidencias)) {

    require_once __DIR__ . '/servicios/ensamblador.php';
    require_once __DIR__ . '/controladores/ControladorLineaInvestigacion.php';

    $controlador = new ControladorLineaInvestigacion(
        crearServicioLineaInvestigacion()
    );

    $clave = (int) urldecode($coincidencias[1]);

    enrutarFicha($controlador, $metodo, $clave, $cuerpo);
    return;
}

// ----------------------------------------------------------------------
// Ninguna ruta coincidió
// ----------------------------------------------------------------------

http_response_code(404);

echo json_encode([
    'estado' => 404,
    'mensaje' => 'Ruta no encontrada.',
    'detalle' => "$metodo $ruta",
], JSON_UNESCAPED_UNICODE);

// ----------------------------------------------------------------------
// Funciones auxiliares del ENRUTADOR
// ----------------------------------------------------------------------

function enrutarFicha(
    object $controlador,
    string $metodo,
    string|int $clave,
    array $cuerpo
): void {
    if ($metodo === 'GET') {
        $controlador->obtener($clave);
    } elseif ($metodo === 'PUT') {
        $controlador->reemplazar($clave, $cuerpo);
    } elseif ($metodo === 'PATCH') {
        $controlador->actualizar($clave, $cuerpo);
    } elseif ($metodo === 'DELETE') {
        $controlador->eliminar($clave);
    } else {
        responderNoPermitido();
    }
}

function responderNoPermitido(): void
{
    http_response_code(405);

    echo json_encode([
        'estado' => 405,
        'mensaje' => 'Método no permitido para esta ruta.',
    ], JSON_UNESCAPED_UNICODE);
}
<?php

declare(strict_types=1);

class ControladorAreaAplicacion
{
    private IServicioAreaAplicacion $servicio;

    public function __construct(IServicioAreaAplicacion $servicio)
    {
        $this->servicio = $servicio;
    }

    public function listar(): void
    {
        $datos = $this->servicio->listar();

        if ($datos === []) {
            http_response_code(204);
            return;
        }

        http_response_code(200);

        echo json_encode([
            'recurso' => 'area_aplicacion',
            'total' => count($datos),
            'datos' => array_map(
                fn(AreaAplicacion $area) => $area->toArray(),
                $datos
            ),
        ], JSON_UNESCAPED_UNICODE);
    }

    public function crear(array $cuerpo): void
    {
        $area = new AreaAplicacion(
            (int) $cuerpo['id'],
            $cuerpo['nombre']
        );

        $this->servicio->crear($area);

        http_response_code(201);

        echo json_encode([
            'estado' => 201,
            'mensaje' => 'Área de aplicación creada exitosamente.',
            'datos' => $area->toArray(),
        ], JSON_UNESCAPED_UNICODE);
    }

    public function obtener(int $id): void
    {
        $area = $this->servicio->obtenerPorId($id);

        if ($area === null) {
            http_response_code(404);

            echo json_encode([
                'estado' => 404,
                'mensaje' => 'Área de aplicación no encontrada.',
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        http_response_code(200);

        echo json_encode(
            $area->toArray(),
            JSON_UNESCAPED_UNICODE
        );
    }

    public function reemplazar(int $id, array $cuerpo): void
    {
        $area = new AreaAplicacion(
            $id,
            $cuerpo['nombre']
        );

        $this->servicio->actualizar($area);

        http_response_code(200);

        echo json_encode([
            'estado' => 200,
            'mensaje' => 'Área de aplicación reemplazada.',
            'filasAfectadas' => 1,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function actualizar(int $id, array $cuerpo): void
    {
        if ($cuerpo === []) {
            throw new InvalidArgumentException(
                'No se envió ningún campo para actualizar.'
            );
        }

        $areaActual = $this->servicio->obtenerPorId($id);

        if ($areaActual === null) {
            http_response_code(404);

            echo json_encode([
                'estado' => 404,
                'mensaje' => 'Área de aplicación no encontrada.',
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        $nombre = $cuerpo['nombre'] ?? $areaActual->getNombre();

        $area = new AreaAplicacion(
            $id,
            $nombre
        );

        $this->servicio->actualizar($area);

        http_response_code(200);

        echo json_encode([
            'estado' => 200,
            'mensaje' => 'Área de aplicación actualizada.',
            'filasAfectadas' => 1,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function eliminar(int $id): void
    {
        $this->servicio->eliminar($id);

        http_response_code(200);

        echo json_encode([
            'estado' => 200,
            'mensaje' => 'Área de aplicación retirada.',
            'filasAfectadas' => 1,
        ], JSON_UNESCAPED_UNICODE);
    }
}
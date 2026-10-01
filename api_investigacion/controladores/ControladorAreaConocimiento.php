<?php

declare(strict_types=1);

class ControladorAreaConocimiento
{
    private IServicioAreaConocimiento $servicio;

    public function __construct(IServicioAreaConocimiento $servicio)
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
            'recurso' => 'area_conocimiento',
            'total' => count($datos),
            'datos' => array_map(
                fn(AreaConocimiento $area) => $area->toArray(),
                $datos
            ),
        ], JSON_UNESCAPED_UNICODE);
    }

    public function crear(array $cuerpo): void
    {
        $area = new AreaConocimiento(
            $cuerpo['id'],
            $cuerpo['granArea'],
            $cuerpo['area'],
            $cuerpo['disciplina']
        );

        $this->servicio->crear($area);

        http_response_code(201);

        echo json_encode([
            'estado' => 201,
            'mensaje' => 'Área de conocimiento creada exitosamente.',
            'datos' => $area->toArray(),
        ], JSON_UNESCAPED_UNICODE);
    }

    public function obtener(string $id): void
    {
        $area = $this->servicio->obtenerPorId($id);

        if ($area === null) {
            http_response_code(404);

            echo json_encode([
                'estado' => 404,
                'mensaje' => 'Área de conocimiento no encontrada.',
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        http_response_code(200);

        echo json_encode(
            $area->toArray(),
            JSON_UNESCAPED_UNICODE
        );
    }

    public function reemplazar(string $id, array $cuerpo): void
    {
        $area = new AreaConocimiento(
            $id,
            $cuerpo['granArea'],
            $cuerpo['area'],
            $cuerpo['disciplina']
        );

        $this->servicio->actualizar($area);

        http_response_code(200);

        echo json_encode([
            'estado' => 200,
            'mensaje' => 'Área de conocimiento reemplazada.',
            'filasAfectadas' => 1,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function actualizar(string $id, array $cuerpo): void
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
                'mensaje' => 'Área de conocimiento no encontrada.',
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        $granArea = $cuerpo['granArea'] ?? $areaActual->getGranArea();
        $areaNombre = $cuerpo['area'] ?? $areaActual->getArea();
        $disciplina = $cuerpo['disciplina'] ?? $areaActual->getDisciplina();

        $area = new AreaConocimiento(
            $id,
            $granArea,
            $areaNombre,
            $disciplina
        );

        $this->servicio->actualizar($area);

        http_response_code(200);

        echo json_encode([
            'estado' => 200,
            'mensaje' => 'Área de conocimiento actualizada.',
            'filasAfectadas' => 1,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function eliminar(string $id): void
    {
        $this->servicio->eliminar($id);

        http_response_code(200);

        echo json_encode([
            'estado' => 200,
            'mensaje' => 'Área de conocimiento retirada.',
            'filasAfectadas' => 1,
        ], JSON_UNESCAPED_UNICODE);
    }
}
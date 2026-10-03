<?php

declare(strict_types=1);

class ControladorObjetivoDesarrolloSostenible
{
    private IServicioObjetivoDesarrolloSostenible $servicio;

    public function __construct(IServicioObjetivoDesarrolloSostenible $servicio)
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
            'recurso' => 'objetivo_desarrollo_sostenible',
            'total' => count($datos),
            'datos' => array_map(
                fn(ObjetivoDesarrolloSostenible $objetivo) => $objetivo->toArray(),
                $datos
            ),
        ], JSON_UNESCAPED_UNICODE);
    }

    public function crear(array $cuerpo): void
    {
        $objetivo = new ObjetivoDesarrolloSostenible(
            (int) $cuerpo['id'],
            $cuerpo['nombre'],
            $cuerpo['categoria']
        );

        $this->servicio->crear($objetivo);

        http_response_code(201);

        echo json_encode([
            'estado' => 201,
            'mensaje' => 'Objetivo de desarrollo sostenible creado exitosamente.',
            'datos' => $objetivo->toArray(),
        ], JSON_UNESCAPED_UNICODE);
    }

    public function obtener(int $id): void
    {
        $objetivo = $this->servicio->obtenerPorId($id);

        if ($objetivo === null) {
            http_response_code(404);

            echo json_encode([
                'estado' => 404,
                'mensaje' => 'Objetivo de desarrollo sostenible no encontrado.',
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        http_response_code(200);

        echo json_encode(
            $objetivo->toArray(),
            JSON_UNESCAPED_UNICODE
        );
    }

    public function reemplazar(int $id, array $cuerpo): void
    {
        $objetivo = new ObjetivoDesarrolloSostenible(
            $id,
            $cuerpo['nombre'],
            $cuerpo['categoria']
        );

        $this->servicio->actualizar($objetivo);

        http_response_code(200);

        echo json_encode([
            'estado' => 200,
            'mensaje' => 'Objetivo de desarrollo sostenible reemplazado.',
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

        $objetivoActual = $this->servicio->obtenerPorId($id);

        if ($objetivoActual === null) {
            http_response_code(404);

            echo json_encode([
                'estado' => 404,
                'mensaje' => 'Objetivo de desarrollo sostenible no encontrado.',
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        $nombre = $cuerpo['nombre'] ?? $objetivoActual->getNombre();
        $categoria = $cuerpo['categoria'] ?? $objetivoActual->getCategoria();

        $objetivo = new ObjetivoDesarrolloSostenible(
            $id,
            $nombre,
            $categoria
        );

        $this->servicio->actualizar($objetivo);

        http_response_code(200);

        echo json_encode([
            'estado' => 200,
            'mensaje' => 'Objetivo de desarrollo sostenible actualizado.',
            'filasAfectadas' => 1,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function eliminar(int $id): void
    {
        $this->servicio->eliminar($id);

        http_response_code(200);

        echo json_encode([
            'estado' => 200,
            'mensaje' => 'Objetivo de desarrollo sostenible retirado.',
            'filasAfectadas' => 1,
        ], JSON_UNESCAPED_UNICODE);
    }
}
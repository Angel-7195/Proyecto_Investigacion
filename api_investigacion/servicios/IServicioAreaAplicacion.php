<?php

declare(strict_types=1);

interface IServicioAreaAplicacion
{
    public function listar(): array;

    public function obtenerPorId(int $id): ?AreaAplicacion;

    public function crear(AreaAplicacion $areaAplicacion): void;

    public function actualizar(AreaAplicacion $areaAplicacion): void;

    public function eliminar(int $id): void;
}
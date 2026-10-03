<?php

declare(strict_types=1);

interface IRepositorioAreaConocimiento
{
    public function listar(): array;

    public function obtenerPorId(string $id): ?AreaConocimiento;

    public function crear(AreaConocimiento $areaConocimiento): void;

    public function actualizar(AreaConocimiento $areaConocimiento): void;

    public function eliminar(string $id): void;
}
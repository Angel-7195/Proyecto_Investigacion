<?php

declare(strict_types=1);

interface IServicioObjetivoDesarrolloSostenible
{
    public function listar(): array;

    public function obtenerPorId(int $id): ?ObjetivoDesarrolloSostenible;

    public function crear(ObjetivoDesarrolloSostenible $objetivo): void;

    public function actualizar(ObjetivoDesarrolloSostenible $objetivo): void;

    public function eliminar(int $id): void;
}
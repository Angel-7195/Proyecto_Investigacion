<?php

declare(strict_types=1);

class ServicioObjetivoDesarrolloSostenible implements IServicioObjetivoDesarrolloSostenible
{
    private IRepositorioObjetivoDesarrolloSostenible $repositorio;

    public function __construct(IRepositorioObjetivoDesarrolloSostenible $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    public function listar(): array
    {
        return $this->repositorio->listar();
    }

    public function obtenerPorId(int $id): ?ObjetivoDesarrolloSostenible
    {
        return $this->repositorio->obtenerPorId($id);
    }

    public function crear(ObjetivoDesarrolloSostenible $objetivo): void
    {
        $this->repositorio->crear($objetivo);
    }

    public function actualizar(ObjetivoDesarrolloSostenible $objetivo): void
    {
        $this->repositorio->actualizar($objetivo);
    }

    public function eliminar(int $id): void
    {
        $this->repositorio->eliminar($id);
    }
}
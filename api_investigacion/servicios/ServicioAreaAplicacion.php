<?php

declare(strict_types=1);

class ServicioAreaAplicacion implements IServicioAreaAplicacion
{
    private IRepositorioAreaAplicacion $repositorio;

    public function __construct(IRepositorioAreaAplicacion $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    public function listar(): array
    {
        return $this->repositorio->listar();
    }

    public function obtenerPorId(int $id): ?AreaAplicacion
    {
        return $this->repositorio->obtenerPorId($id);
    }

    public function crear(AreaAplicacion $areaAplicacion): void
    {
        $this->repositorio->crear($areaAplicacion);
    }

    public function actualizar(AreaAplicacion $areaAplicacion): void
    {
        $this->repositorio->actualizar($areaAplicacion);
    }

    public function eliminar(int $id): void
    {
        $this->repositorio->eliminar($id);
    }
}
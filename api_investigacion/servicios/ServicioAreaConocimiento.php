<?php

declare(strict_types=1);

class ServicioAreaConocimiento implements IServicioAreaConocimiento
{
    private IRepositorioAreaConocimiento $repositorio;

    public function __construct(IRepositorioAreaConocimiento $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    public function listar(): array
    {
        return $this->repositorio->listar();
    }

    public function obtenerPorId(string $id): ?AreaConocimiento
    {
        return $this->repositorio->obtenerPorId($id);
    }

    public function crear(AreaConocimiento $areaConocimiento): void
    {
        $this->repositorio->crear($areaConocimiento);
    }

    public function actualizar(AreaConocimiento $areaConocimiento): void
    {
        $this->repositorio->actualizar($areaConocimiento);
    }

    public function eliminar(string $id): void
    {
        $this->repositorio->eliminar($id);
    }
}
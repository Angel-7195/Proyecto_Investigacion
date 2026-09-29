<?php

class AreaAplicacion
{
    private int $id;
    private string $nombre;
    private bool $activo;

    public function __construct(
        int $id,
        string $nombre,
        bool $activo = true
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->activo = $activo;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function isActivo(): bool
    {
        return $this->activo;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function desactivar(): void
    {
        $this->activo = false;
    }

    public function activar(): void
    {
        $this->activo = true;
    }
}
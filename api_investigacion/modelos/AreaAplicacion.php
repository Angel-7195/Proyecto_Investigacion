<?php

declare(strict_types=1);

class AreaAplicacion
{
    private int $id;
    private string $nombre;

    public function __construct(
        int $id,
        string $nombre
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
        ];
    }
}
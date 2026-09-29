<?php

declare(strict_types=1);

class ObjetivoDesarrolloSostenible
{
    private int $id;
    private string $nombre;
    private string $categoria;

    public function __construct(
        int $id,
        string $nombre,
        string $categoria
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->categoria = $categoria;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getCategoria(): string
    {
        return $this->categoria;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setCategoria(string $categoria): void
    {
        $this->categoria = $categoria;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'categoria' => $this->categoria,
        ];
    }
}
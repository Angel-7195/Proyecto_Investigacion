<?php

declare(strict_types=1);

/**
 * Representa una universidad del módulo de Investigación.
 *
 * El campo id es la llave primaria y no puede modificarse
 * después de crear el objeto.
 *
 * El campo activo no pertenece a la ficha editable:
 * se utiliza únicamente para el borrado lógico.
 */
class Universidad
{
    private int $id;
    private string $nombre;
    private string $tipo;
    private string $ciudad;

    public function __construct(
        int $id,
        string $nombre,
        string $tipo,
        string $ciudad
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->ciudad = $ciudad;
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

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function getCiudad(): string
    {
        return $this->ciudad;
    }

    public function setCiudad(string $ciudad): void
    {
        $this->ciudad = $ciudad;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
            'ciudad' => $this->ciudad,
        ];
    }
}
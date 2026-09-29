<?php

class AreaConocimiento
{
    private string $id;
    private string $granArea;
    private string $area;
    private string $disciplina;
    private bool $activo;

    public function __construct(
        string $id,
        string $granArea,
        string $area,
        string $disciplina,
        bool $activo = true
    ) {
        $this->id = $id;
        $this->granArea = $granArea;
        $this->area = $area;
        $this->disciplina = $disciplina;
        $this->activo = $activo;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGranArea(): string
    {
        return $this->granArea;
    }

    public function getArea(): string
    {
        return $this->area;
    }

    public function getDisciplina(): string
    {
        return $this->disciplina;
    }

    public function isActivo(): bool
    {
        return $this->activo;
    }

    public function setGranArea(string $granArea): void
    {
        $this->granArea = $granArea;
    }

    public function setArea(string $area): void
    {
        $this->area = $area;
    }

    public function setDisciplina(string $disciplina): void
    {
        $this->disciplina = $disciplina;
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
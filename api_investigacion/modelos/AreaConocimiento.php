<?php

declare(strict_types=1);

class AreaConocimiento
{
    private string $id;
    private string $granArea;
    private string $area;
    private string $disciplina;

    public function __construct(
        string $id,
        string $granArea,
        string $area,
        string $disciplina
    ) {
        $this->id = $id;
        $this->granArea = $granArea;
        $this->area = $area;
        $this->disciplina = $disciplina;
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'gran_area' => $this->granArea,
            'area' => $this->area,
            'disciplina' => $this->disciplina,
        ];
    }
}
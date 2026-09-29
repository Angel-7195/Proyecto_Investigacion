<?php

declare(strict_types=1);

/**
 * Representa un término clave del módulo de investigación.
 *
 * La propiedad "activo" no forma parte del modelo porque se utiliza
 * únicamente para el borrado lógico desde el repositorio.
 */
class TerminoClave
{
    private string $termino;
    private ?string $terminoIngles;

    public function __construct(
        string $termino,
        ?string $terminoIngles = null
    ) {
        $this->termino = $termino;
        $this->terminoIngles = $terminoIngles;
    }

    public function getTermino(): string
    {
        return $this->termino;
    }

    public function getTerminoIngles(): ?string
    {
        return $this->terminoIngles;
    }

    public function setTerminoIngles(?string $terminoIngles): void
    {
        $this->terminoIngles = $terminoIngles;
    }

    public function toArray(): array
    {
        return [
            'termino' => $this->termino,
            'termino_ingles' => $this->terminoIngles,
        ];
    }
}
<?php

declare(strict_types=1);

class RepositorioObjetivoDesarrolloSostenibleMariaDB implements IRepositorioObjetivoDesarrolloSostenible
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function listar(): array
    {
        $sql = "
            SELECT id, nombre, categoria
            FROM objetivo_desarrollo_sostenible
            WHERE activo = TRUE
            ORDER BY id
        ";

        $sentencia = $this->conexion->query($sql);

        $objetivos = [];

        while ($fila = $sentencia->fetch(PDO::FETCH_ASSOC)) {
            $objetivos[] = new ObjetivoDesarrolloSostenible(
                (int) $fila['id'],
                $fila['nombre'],
                $fila['categoria']
            );
        }

        return $objetivos;
    }

    public function obtenerPorId(int $id): ?ObjetivoDesarrolloSostenible
    {
        $sql = "
            SELECT id, nombre, categoria
            FROM objetivo_desarrollo_sostenible
            WHERE id = :id
              AND activo = TRUE
        ";

        $sentencia = $this->conexion->prepare($sql);
        $sentencia->execute([':id' => $id]);

        $fila = $sentencia->fetch(PDO::FETCH_ASSOC);

        if ($fila === false) {
            return null;
        }

        return new ObjetivoDesarrolloSostenible(
            (int) $fila['id'],
            $fila['nombre'],
            $fila['categoria']
        );
    }

    public function crear(ObjetivoDesarrolloSostenible $objetivo): void
    {
        $sql = "
            INSERT INTO objetivo_desarrollo_sostenible
                (id, nombre, categoria, activo)
            VALUES
                (:id, :nombre, :categoria, TRUE)
        ";

        $sentencia = $this->conexion->prepare($sql);

        $sentencia->execute([
            ':id' => $objetivo->getId(),
            ':nombre' => $objetivo->getNombre(),
            ':categoria' => $objetivo->getCategoria(),
        ]);
    }

    public function actualizar(ObjetivoDesarrolloSostenible $objetivo): void
    {
        $sql = "
            UPDATE objetivo_desarrollo_sostenible
            SET nombre = :nombre,
                categoria = :categoria
            WHERE id = :id
              AND activo = TRUE
        ";

        $sentencia = $this->conexion->prepare($sql);

        $sentencia->execute([
            ':id' => $objetivo->getId(),
            ':nombre' => $objetivo->getNombre(),
            ':categoria' => $objetivo->getCategoria(),
        ]);
    }

    public function eliminar(int $id): void
    {
        $sql = "
            UPDATE objetivo_desarrollo_sostenible
            SET activo = FALSE
            WHERE id = :id
              AND activo = TRUE
        ";

        $sentencia = $this->conexion->prepare($sql);
        $sentencia->execute([':id' => $id]);
    }
}
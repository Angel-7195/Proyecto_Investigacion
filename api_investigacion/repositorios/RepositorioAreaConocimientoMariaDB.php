<?php

declare(strict_types=1);

class RepositorioAreaConocimientoMariaDB implements IRepositorioAreaConocimiento
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function listar(): array
    {
        $sql = "
            SELECT id, gran_area, area, disciplina
            FROM area_conocimiento
            WHERE activo = TRUE
            ORDER BY id
        ";

        $sentencia = $this->conexion->query($sql);

        $areas = [];

        while ($fila = $sentencia->fetch(PDO::FETCH_ASSOC)) {
            $areas[] = new AreaConocimiento(
                $fila['id'],
                $fila['gran_area'],
                $fila['area'],
                $fila['disciplina']
            );
        }

        return $areas;
    }

    public function obtenerPorId(string $id): ?AreaConocimiento
    {
        $sql = "
            SELECT id, gran_area, area, disciplina
            FROM area_conocimiento
            WHERE id = :id
              AND activo = TRUE
        ";

        $sentencia = $this->conexion->prepare($sql);

        $sentencia->execute([
            ':id' => $id
        ]);

        $fila = $sentencia->fetch(PDO::FETCH_ASSOC);

        if ($fila === false) {
            return null;
        }

        return new AreaConocimiento(
            $fila['id'],
            $fila['gran_area'],
            $fila['area'],
            $fila['disciplina']
        );
    }

    public function crear(AreaConocimiento $areaConocimiento): void
    {
        $sql = "
            INSERT INTO area_conocimiento
                (id, gran_area, area, disciplina, activo)
            VALUES
                (:id, :gran_area, :area, :disciplina, TRUE)
        ";

        $sentencia = $this->conexion->prepare($sql);

        $sentencia->execute([
            ':id' => $areaConocimiento->getId(),
            ':gran_area' => $areaConocimiento->getGranArea(),
            ':area' => $areaConocimiento->getArea(),
            ':disciplina' => $areaConocimiento->getDisciplina()
        ]);
    }

    public function actualizar(AreaConocimiento $areaConocimiento): void
    {
        $sql = "
            UPDATE area_conocimiento
            SET gran_area = :gran_area,
                area = :area,
                disciplina = :disciplina
            WHERE id = :id
              AND activo = TRUE
        ";

        $sentencia = $this->conexion->prepare($sql);

        $sentencia->execute([
            ':id' => $areaConocimiento->getId(),
            ':gran_area' => $areaConocimiento->getGranArea(),
            ':area' => $areaConocimiento->getArea(),
            ':disciplina' => $areaConocimiento->getDisciplina()
        ]);
    }

    public function eliminar(string $id): void
    {
        $sql = "
            UPDATE area_conocimiento
            SET activo = FALSE
            WHERE id = :id
              AND activo = TRUE
        ";

        $sentencia = $this->conexion->prepare($sql);

        $sentencia->execute([
            ':id' => $id
        ]);
    }
}
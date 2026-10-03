<?php

declare(strict_types=1);

class RepositorioAreaAplicacionMariaDB implements IRepositorioAreaAplicacion
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function listar(): array
    {
        $sql = "
            SELECT id, nombre
            FROM area_aplicacion
            WHERE activo = TRUE
            ORDER BY id
        ";

        $sentencia = $this->conexion->query($sql);

        $areas = [];

        while ($fila = $sentencia->fetch(PDO::FETCH_ASSOC)) {
            $areas[] = new AreaAplicacion(
                (int) $fila['id'],
                $fila['nombre']
            );
        }

        return $areas;
    }

    public function obtenerPorId(int $id): ?AreaAplicacion
    {
        $sql = "
            SELECT id, nombre
            FROM area_aplicacion
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

        return new AreaAplicacion(
            (int) $fila['id'],
            $fila['nombre']
        );
    }

    public function crear(AreaAplicacion $areaAplicacion): void
    {
        $sql = "
            INSERT INTO area_aplicacion (id, nombre, activo)
            VALUES (:id, :nombre, TRUE)
        ";

        $sentencia = $this->conexion->prepare($sql);

        $sentencia->execute([
            ':id' => $areaAplicacion->getId(),
            ':nombre' => $areaAplicacion->getNombre()
        ]);
    }

    public function actualizar(AreaAplicacion $areaAplicacion): void
    {
        $sql = "
            UPDATE area_aplicacion
            SET nombre = :nombre
            WHERE id = :id
              AND activo = TRUE
        ";

        $sentencia = $this->conexion->prepare($sql);

        $sentencia->execute([
            ':id' => $areaAplicacion->getId(),
            ':nombre' => $areaAplicacion->getNombre()
        ]);
    }

    public function eliminar(int $id): void
    {
        $sql = "
            UPDATE area_aplicacion
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
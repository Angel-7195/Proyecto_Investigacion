<?php

declare(strict_types=1);

require_once __DIR__ . '/../servicios/ServicioTerminoClave.php';
require_once __DIR__ . '/../excepciones/ConflictoExcepcion.php';

/**
 * Repositorio falso para probar ServicioTerminoClave
 * sin utilizar PDO ni MariaDB.
 */
class RepositorioTerminoClaveFalsoEnMemoria
    implements IRepositorioTerminoClave
{
    private array $registros = [];

    public function obtenerTodos(): array
    {
        $activos = [];

        foreach ($this->registros as $registro) {
            if ($registro['activo']) {
                $activos[] = $registro['modelo'];
            }
        }

        return $activos;
    }

    public function obtenerPorTermino(
        string $termino
    ): ?TerminoClave {
        if (!array_key_exists($termino, $this->registros)) {
            return null;
        }

        if (!$this->registros[$termino]['activo']) {
            return null;
        }

        return $this->registros[$termino]['modelo'];
    }

    public function crear(TerminoClave $terminoClave): int
    {
        $termino = $terminoClave->getTermino();

        /*
         * Una llave retirada sigue ocupada.
         * Por eso también se rechaza si el registro está inactivo.
         */
        if (array_key_exists($termino, $this->registros)) {
            throw new ConflictoExcepcion(
                'Ya existe un término clave con esa llave.'
            );
        }

        $this->registros[$termino] = [
            'modelo' => $terminoClave,
            'activo' => true,
        ];

        return 1;
    }

    public function reemplazar(
        string $termino,
        ?string $terminoIngles
    ): int {
        $terminoClave = $this->obtenerPorTermino($termino);

        if ($terminoClave === null) {
            return 0;
        }

        $terminoClave->setTerminoIngles($terminoIngles);

        return 1;
    }

    public function actualizar(
        string $termino,
        array $datos
    ): int {
        $terminoClave = $this->obtenerPorTermino($termino);

        if ($terminoClave === null) {
            return 0;
        }

        if (array_key_exists('termino_ingles', $datos)) {
            $terminoClave->setTerminoIngles(
                $datos['termino_ingles']
            );

            return 1;
        }

        return 0;
    }

    public function retirar(string $termino): int
    {
        if (!array_key_exists($termino, $this->registros)) {
            return 0;
        }

        if (!$this->registros[$termino]['activo']) {
            return 0;
        }

        $this->registros[$termino]['activo'] = false;

        return 1;
    }
}

/**
 * Contador general de pruebas fallidas.
 */
$fallos = 0;

function afirmar(bool $condicion, string $mensaje): void
{
    if (!$condicion) {
        throw new RuntimeException($mensaje);
    }
}

function probar(string $nombre, callable $prueba): void
{
    global $fallos;

    try {
        $prueba();

        echo "[OK] {$nombre}" . PHP_EOL;
    } catch (Throwable $excepcion) {
        $fallos++;

        echo "[ERROR] {$nombre}: "
            . $excepcion->getMessage()
            . PHP_EOL;
    }
}

/*
 * Aquí conectamos el servicio a la interfaz falsa,
 * no a RepositorioTerminoClaveMariaDB.
 */
$repositorio = new RepositorioTerminoClaveFalsoEnMemoria();
$servicio = new ServicioTerminoClave($repositorio);

// ----------------------------------------------------------------------
// TERMINO CLAVE
// ----------------------------------------------------------------------

probar('crear termino clave', function () use ($servicio): void {
    $creado = $servicio->crear(
        new TerminoClave(
            'inteligencia artificial',
            'artificial intelligence'
        )
    );

    afirmar(
        $creado->getTermino() === 'inteligencia artificial',
        'El término creado no coincide.'
    );
});

probar('listar registros activos', function () use ($servicio): void {
    $registros = $servicio->listar();

    afirmar(
        count($registros) === 1,
        'Se esperaba exactamente un registro activo.'
    );
});

probar('obtener registro activo', function () use ($servicio): void {
    $registro = $servicio->obtenerPorTermino(
        'inteligencia artificial'
    );

    afirmar(
        $registro->getTerminoIngles()
            === 'artificial intelligence',
        'La traducción no coincide.'
    );
});

probar('recurso inexistente', function () use ($servicio): void {
    try {
        $servicio->obtenerPorTermino('no existe');

        throw new RuntimeException(
            'No se lanzó NoEncontradoExcepcion.'
        );
    } catch (NoEncontradoExcepcion) {
        // Resultado esperado.
    }
});

probar('PUT completo', function () use ($servicio): void {
    $filas = $servicio->reemplazar(
        'inteligencia artificial',
        'artificial intelligence updated'
    );

    afirmar(
        $filas === 1,
        'PUT no reportó una fila afectada.'
    );

    $registro = $servicio->obtenerPorTermino(
        'inteligencia artificial'
    );

    afirmar(
        $registro->getTerminoIngles()
            === 'artificial intelligence updated',
        'PUT no reemplazó el valor.'
    );
});

probar('PATCH parcial', function () use ($servicio): void {
    $filas = $servicio->actualizar(
        'inteligencia artificial',
        [
            'termino_ingles' => null,
        ]
    );

    afirmar(
        $filas === 1,
        'PATCH no reportó una fila afectada.'
    );

    $registro = $servicio->obtenerPorTermino(
        'inteligencia artificial'
    );

    afirmar(
        $registro->getTerminoIngles() === null,
        'PATCH no permitió establecer null.'
    );
});

probar('PATCH vacío', function () use ($servicio): void {
    try {
        $servicio->actualizar(
            'inteligencia artificial',
            []
        );

        throw new RuntimeException(
            'PATCH vacío fue aceptado.'
        );
    } catch (InvalidArgumentException) {
        // Resultado esperado.
    }
});

probar('llave duplicada', function () use ($servicio): void {
    try {
        $servicio->crear(
            new TerminoClave(
                'inteligencia artificial',
                'duplicado'
            )
        );

        throw new RuntimeException(
            'La llave duplicada fue aceptada.'
        );
    } catch (ConflictoExcepcion) {
        // Resultado esperado.
    }
});

probar('retirar registro', function () use ($servicio): void {
    $filas = $servicio->retirar(
        'inteligencia artificial'
    );

    afirmar(
        $filas === 1,
        'El retiro no afectó una fila.'
    );
});

probar('registro retirado no se puede consultar', function () use ($servicio): void {
    try {
        $servicio->obtenerPorTermino(
            'inteligencia artificial'
        );

        throw new RuntimeException(
            'El registro retirado todavía aparece activo.'
        );
    } catch (NoEncontradoExcepcion) {
        // Resultado esperado.
    }
});

probar('registro retirado no aparece en listado', function () use ($servicio): void {
    afirmar(
        $servicio->listar() === [],
        'El registro retirado todavía aparece en el listado.'
    );
});

probar('segundo retiro produce NoEncontradoExcepcion', function () use ($servicio): void {
    try {
        $servicio->retirar(
            'inteligencia artificial'
        );

        throw new RuntimeException(
            'El segundo retiro fue aceptado.'
        );
    } catch (NoEncontradoExcepcion) {
        // Resultado esperado.
    }
});

probar('llave retirada sigue ocupada', function () use ($servicio): void {
    try {
        $servicio->crear(
            new TerminoClave(
                'inteligencia artificial',
                'new value'
            )
        );

        throw new RuntimeException(
            'Se reutilizó una llave retirada.'
        );
    } catch (ConflictoExcepcion) {
        // Resultado esperado.
    }
});

// ----------------------------------------------------------------------
// RESULTADO FINAL
// ----------------------------------------------------------------------

echo PHP_EOL;

if ($fallos === 0) {
    echo '[OK] Todas las pruebas de termino_clave pasaron.'
        . PHP_EOL;

    exit(0);
}

echo "[ERROR] Total de pruebas fallidas: {$fallos}"
    . PHP_EOL;

exit(1);
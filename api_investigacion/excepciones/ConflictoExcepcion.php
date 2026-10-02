<?php

declare(strict_types=1);

/**
 * Indica un conflicto con los datos almacenados,
 * por ejemplo una llave primaria ya ocupada.
 */
class ConflictoExcepcion extends RuntimeException
{
}
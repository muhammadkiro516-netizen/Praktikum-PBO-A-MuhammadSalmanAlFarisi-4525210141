<?php
declare(strict_types=1);

namespace Perpustakaan\Exception;

use RuntimeException;

/** Induk seluruh exception domain (sesi 10). PHP tidak mengenal checked exception. */
abstract class PerpustakaanException extends RuntimeException
{
}

<?php
declare(strict_types=1);

namespace App\Domain\Exception;

/** Thrown when a reservation is not found but required. */
final class NotFoundException extends \RuntimeException {}

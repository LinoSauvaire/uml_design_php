<?php
declare(strict_types=1);

namespace App\Domain\Exception;

/** Thrown when a domain rule is violated (e.g., overlap). */
final class DomainConflictException extends \RuntimeException {}

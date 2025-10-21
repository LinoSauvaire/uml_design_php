<?php
declare(strict_types=1);

namespace App\Domain;

/**
 * Class Resource
 * Responsibility: represent a bookable resource (e.g., a room).
 * Invariants:
 * - id and name are non-empty trimmed strings.
 */
final class Resource
{
    private string $id;
    private string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = trim($id);
        $this->name = trim($name);
    }

    public function id(): string { return $this->id; }
    public function name(): string { return $this->name; }
}

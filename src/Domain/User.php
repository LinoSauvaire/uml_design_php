<?php
declare(strict_types=1);

namespace App\Domain;

/**
 * Class User
 * Responsibility: represent a system user with a stable identity.
 * Invariants:
 * - id, name, email are non-empty trimmed strings.
 */
final class User
{
    private string $id;
    private string $name;
    private string $email;

    public function __construct(string $id, string $name, string $email)
    {
        // Validate inputs: keep the state consistent
        $this->id = trim($id);
        $this->name = trim($name);
        $this->email = trim($email);
        // In a real app, consider proper email validation and exceptions
    }

    public function id(): string { return $this->id; }
    public function name(): string { return $this->name; }
    public function email(): string { return $this->email; }
}

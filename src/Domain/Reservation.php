<?php
declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;

/**
 * Entity: Reservation with simple cancellation behavior.
 */
final class Reservation
{
    private string $id;
    private User $user;
    private Resource $resource;
    private Period $period;
    private ?DateTimeImmutable $cancelledAt = null;

    public function __construct(string $id, User $user, Resource $resource, Period $period)
    {
        $this->id = trim($id);
        $this->user = $user;
        $this->resource = $resource;
        $this->period = $period;
    }

    public function id(): string { return $this->id; }
    public function user(): User { return $this->user; }
    public function resource(): Resource { return $this->resource; }
    public function period(): Period { return $this->period; }

    /** A reservation is active if not cancelled and not ended yet. */
    public function isActiveAt(DateTimeImmutable $now): bool
    {
        return $this->cancelledAt === null && $this->period->end() > $now;
    }

    /** Idempotent cancellation: calling twice has no additional effect. */
    public function cancel(DateTimeImmutable $when): void
    {
        if ($this->cancelledAt === null) {
            $this->cancelledAt = $when;
        }
    }
}

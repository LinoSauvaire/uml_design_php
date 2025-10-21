<?php
declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;

/**
 * Class Reservation
 * Responsibility: link a User to a Resource on a valid time range.
 * Invariants:
 * - endAt must be strictly greater than startAt.
 * - user and resource are mandatory.
 */
final class Reservation
{
    private string $id;
    private User $user;
    private Resource $resource;
    private DateTimeImmutable $startAt;
    private DateTimeImmutable $endAt;

    public function __construct(
        string $id,
        User $user,
        Resource $resource,
        DateTimeImmutable $startAt,
        DateTimeImmutable $endAt
    ) {
        $this->id = trim($id);
        $this->user = $user;
        $this->resource = $resource;
        $this->startAt = $startAt;
        $this->endAt = $endAt;

        if ($this->endAt <= $this->startAt) {
            throw new \InvalidArgumentException('End must be after start.');
        }
    }

    public function id(): string { return $this->id; }
    public function user(): User { return $this->user; }
    public function resource(): Resource { return $this->resource; }
    public function startAt(): DateTimeImmutable { return $this->startAt; }
    public function endAt(): DateTimeImmutable { return $this->endAt; }

    /**
     * Check time overlap with another reservation on the same resource.
     * Rule: [a,b) overlaps [c,d) iff a < d and c < b
     */
    public function conflictsWith(Reservation $other): bool
    {
        if ($this->resource->id() !== $other->resource()->id()) {
            return false;
        }
        return $this->startAt < $other->endAt() && $other->startAt() < $this->endAt;
    }
}

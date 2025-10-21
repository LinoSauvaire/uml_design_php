<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\ReservationRepositoryInterface;
use App\Domain\{Reservation, Resource, User, Period};

/**
 * In-memory repository backed by a PHP array.
 * Fast to develop, easy to test, no external dependency.
 */
final class InMemoryReservationRepository implements ReservationRepositoryInterface
{
    /** @var array<string,Reservation> */
    private array $byId = [];

    public function save(Reservation $reservation): void
    {
        $this->byId[$reservation->id()] = $reservation;
    }

    public function findById(string $id): ?Reservation
    {
        return $this->byId[$id] ?? null;
    }

    public function findActiveByUser(User $user, \DateTimeImmutable $now): array
    {
        $out = [];
        foreach ($this->byId as $r) {
            if ($r->user()->id() === $user->id() && $r->isActiveAt($now)) {
                $out[] = $r;
            }
        }
        return $out;
    }

    public function findOverlaps(Resource $resource, Period $period): array
    {
        $out = [];
        foreach ($this->byId as $r) {
            if ($r->resource()->id() === $resource->id() && $r->period()->overlaps($period)) {
                $out[] = $r;
            }
        }
        return $out;
    }
}

<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\{Reservation, Resource, User, Period};

/**
 * Port interface: storage contract for reservations.
 * The implementation may be in-memory for now.
 */
interface ReservationRepositoryInterface
{
    public function save(Reservation $reservation): void;
    public function findById(string $id): ?Reservation;
    public function findActiveByUser(User $user, \DateTimeImmutable $now): array;
    public function findOverlaps(Resource $resource, Period $period): array;
}

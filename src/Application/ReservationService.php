<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\{Reservation, User, Resource, Period};
use App\Domain\Exception\{DomainConflictException, NotFoundException};

/**
 * Application Service: holds use cases for reservations.
 */
final class ReservationService
{
    private ReservationRepositoryInterface $repo;
    /** @var callable():string */
    private $idFactory;
    /** @var callable():\DateTimeImmutable */
    private $clock;

    /**
     * @param callable():string $idFactory Unique id generator
     * @param callable():\DateTimeImmutable $clock Time provider (now)
     */
    public function __construct(
        ReservationRepositoryInterface $repo,
        callable $idFactory,
        callable $clock
    ) {
        $this->repo = $repo;
        $this->idFactory = $idFactory;
        $this->clock = $clock;
    }

    /** Use case: create a reservation if no time overlap exists. */
    public function book(User $user, Resource $resource, Period $period): Reservation
    {
        $overlaps = $this->repo->findOverlaps($resource, $period);
        if (!empty($overlaps)) {
            throw new DomainConflictException('Overlapping reservation detected.');
        }
        $id = ($this->idFactory)();
        $reservation = new Reservation($id, $user, $resource, $period);
        $this->repo->save($reservation);
        return $reservation;
    }

    /** Use case: cancel an existing reservation (idempotent). */
    public function cancel(string $reservationId): void
    {
        $found = $this->repo->findById($reservationId);
        if ($found === null) {
            throw new NotFoundException('Reservation not found.');
        }
        $now = ($this->clock)();
        $found->cancel($now);
        $this->repo->save($found);
    }

    /** Use case: list active reservations for a given user at "now". */
    public function listForUser(User $user): array
    {
        $now = ($this->clock)();
        return $this->repo->findActiveByUser($user, $now);
    }
}

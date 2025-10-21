<?php
declare(strict_types=1);

use App\Domain\{Reservation, User, Resource, Period};
use App\Application\ReservationService;
use App\Infrastructure\InMemoryReservationRepository;
use App\Domain\Exception\DomainConflictException;
use PHPUnit\Framework\TestCase;

final class TestBook extends TestCase
{
    public function testBookCreation(): void
    {
        $repo = new InMemoryReservationRepository();
        $idFactory = fn() => 'res-1';
        $clock = fn() => new \DateTimeImmutable('2025-10-21T09:00:00Z');
        $service = new ReservationService($repo, $idFactory, $clock);

        $user = new User('u-1', 'Alice', 'alice@example.com');
        $resource = new Resource('r-1', 'Room 1');
        $start = new \DateTimeImmutable('2025-10-21T10:00:00Z');
        $end = new \DateTimeImmutable('2025-10-21T11:00:00Z');
        $period = new Period($start, $end);

        $reservation = $service->book($user, $resource, $period);

        $this->assertSame('res-1', $reservation->id());
        $list = $service->listForUser($user);
        $this->assertCount(1, $list);
        $this->assertSame($reservation->id(), $list[0]->id());
    }

    public function testBookConflictThrows(): void
    {
        $repo = new InMemoryReservationRepository();
        $idFactory = fn() => 'res-2';
        $clock = fn() => new \DateTimeImmutable('2025-10-21T09:00:00Z');
        $service = new ReservationService($repo, $idFactory, $clock);

        $user = new User('u-2', 'Bob', 'bob@example.com');
        $resource = new Resource('r-2', 'Room 2');

        $existing = new Reservation(
            'existing-1',
            $user,
            $resource,
            new Period(new \DateTimeImmutable('2025-10-21T10:00:00Z'), new \DateTimeImmutable('2025-10-21T11:00:00Z'))
        );
        $repo->save($existing);

        $period = new Period(new \DateTimeImmutable('2025-10-21T10:30:00Z'), new \DateTimeImmutable('2025-10-21T11:30:00Z'));

        $this->expectException(DomainConflictException::class);
        $service->book($user, $resource, $period);
    }
}
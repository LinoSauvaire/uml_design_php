<?php
declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;

/**
 * Value Object: immutable period with validation and overlap check.
 */
final class Period
{
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($end <= $start) {
            throw new \InvalidArgumentException('End must be strictly after start.');
        }
        $this->start = $start;
        $this->end = $end;
    }

    public function start(): DateTimeImmutable { return $this->start; }
    public function end(): DateTimeImmutable { return $this->end; }

    /** Overlap rule: [a,b) overlaps [c,d) iff a < d and c < b */
    public function overlaps(Period $other): bool
    {
        return $this->start < $other->end() && $other->start() < $this->end;
    }
}

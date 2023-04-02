<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\ValueObjects;

use DateTimeImmutable;

final class DateTimeRangeValueObject
{
    public function __construct(
        public readonly ?DateTimeImmutable $start,
        public readonly ?DateTimeImmutable $end,
    ) {}
}

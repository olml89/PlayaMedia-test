<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\ValueObjects;

class NullableBoolValueObject
{
    public function __construct(
        private readonly ?bool $value,
    ) {}

    public function toBool(): ?bool
    {
        return $this->value;
    }
}

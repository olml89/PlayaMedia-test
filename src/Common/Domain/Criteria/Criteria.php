<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Criteria;

final class Criteria
{
    public function __construct(
        private readonly ?Expression $expression,
    ) {}

    public function expression(): ?Expression
    {
        return $this->expression;
    }
}

<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Domain\Specifications;

use olml89\PlayaMedia\Common\Domain\Criteria\Expression;
use olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions\NotExpression;
use olml89\PlayaMedia\User\Domain\User;

final class NotSpecification implements Specification
{
    public function __construct(
        private readonly Specification $specification,
    ) {}

    public function isSatisfiedBy(User $user): bool
    {
        return !$this->specification->isSatisfiedBy($user);
    }

    public function expression(): Expression
    {
        return new NotExpression($this->specification->expression());
    }
}

<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Domain\Specifications;

use olml89\PlayaMedia\Common\Domain\Criteria\Expression;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilter;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilterField;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilterOperator;
use olml89\PlayaMedia\User\Domain\User;

final class IsActiveSpecification implements Specification
{
    public function __construct(
        private readonly ?bool $isActive,
    ) {}

    public function isSatisfiedBy(User $user): bool
    {
        return $this->isActive === $user->isActive();
    }

    public function expression(): Expression
    {
        return new SearchFilter(
            field: new SearchFilterField('isActive'),
            operator: SearchFilterOperator::EQUAL,
            value: $this->isActive,
        );
    }
}

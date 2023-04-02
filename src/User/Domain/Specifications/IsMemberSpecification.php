<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Domain\Specifications;

use olml89\PlayaMedia\Common\Domain\Criteria\Expression;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilter;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilterField;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilterOperator;
use olml89\PlayaMedia\User\Domain\User;

final class IsMemberSpecification implements Specification
{
    public function __construct(
        private readonly bool $isMember,
    ) {}

    public function isSatisfiedBy(User $user): bool
    {
        return $this->isMember === $user->isMember();
    }

    public function expression(): Expression
    {
        return new SearchFilter(
            field: new SearchFilterField('isMember'),
            operator: SearchFilterOperator::EQUAL,
            value: $this->isMember,
        );
    }
}

<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Domain\Specifications;

use olml89\PlayaMedia\Common\Domain\Criteria\Expression;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilter;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilterField;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilterOperator;
use olml89\PlayaMedia\User\Domain\User;
use olml89\PlayaMedia\User\Domain\UserType;

final class UserTypeSpecification implements Specification
{
    public function __construct(
        private readonly UserType $userType,
    ) {}

    public function isSatisfiedBy(User $user): bool
    {
        return $this->userType === $user->userType();
    }

    public function expression(): Expression
    {
        return new SearchFilter(
            field: new SearchFilterField('userType'),
            operator: SearchFilterOperator::EQUAL,
            value: $this->userType,
        );
    }
}

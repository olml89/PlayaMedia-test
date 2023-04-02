<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Domain\Specifications;

use olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions\AndExpression;
use olml89\PlayaMedia\Common\Domain\Criteria\Expression;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilter;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilterField;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilterOperator;
use olml89\PlayaMedia\Common\Domain\ValueObjects\DateTimeRangeValueObject;
use olml89\PlayaMedia\User\Domain\User;

final class LastLoginAtSpecification implements Specification
{
    public function __construct(
        private readonly DateTimeRangeValueObject $lastLoginAtRange
    )
    {}

    public function isSatisfiedBy(User $user): bool
    {
        $lowerBound = is_null($this->lastLoginAtRange->start)
            || $user->lastLoginAt() >= $this->lastLoginAtRange->start;

        $upperBound = is_null($this->lastLoginAtRange->end)
            || $user->lastLoginAt() <= $this->lastLoginAtRange->end;

        return $lowerBound && $upperBound;
    }

    public function expression(): Expression
    {
        $clauses = [];

        if (!is_null($this->lastLoginAtRange->start)) {
            $clauses[] = new SearchFilter(
                field: new SearchFilterField('lastLoginAt'),
                operator: SearchFilterOperator::GTE,
                value: $this->lastLoginAtRange->start,
            );
        }

        if (!is_null($this->lastLoginAtRange->end)) {
            $clauses[] = new SearchFilter(
                field: new SearchFilterField('lastLoginAt'),
                operator: SearchFilterOperator::LTE,
                value: $this->lastLoginAtRange->end,
            );
        }

        return new AndExpression(...$clauses);
    }
}

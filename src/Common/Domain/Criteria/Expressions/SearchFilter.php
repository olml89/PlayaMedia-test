<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Criteria\Expressions;

use olml89\PlayaMedia\Common\Domain\Criteria\Expression;

final class SearchFilter implements Expression
{
    public function __construct(
        private readonly SearchFilterField $field,
        private readonly SearchFilterOperator $operator,
        private readonly mixed $value,
    ) {}

    public function field(): SearchFilterField
    {
        return $this->field;
    }

    public function operator(): SearchFilterOperator
    {
        return $this->operator;
    }

    public function value(): mixed
    {
        return $this->value;
    }
}

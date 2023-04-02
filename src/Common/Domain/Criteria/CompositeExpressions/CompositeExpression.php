<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions;

use olml89\PlayaMedia\Common\Domain\Criteria\Expression;

abstract class CompositeExpression implements Expression
{
    public function __construct(
        protected readonly ExpressionType $type,
    ) {}

    public function type(): ExpressionType
    {
        return $this->type;
    }
}

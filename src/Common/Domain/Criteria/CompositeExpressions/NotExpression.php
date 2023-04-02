<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions;

use olml89\PlayaMedia\Common\Domain\Criteria\Expression;

final class NotExpression extends CompositeExpression
{
    private readonly Expression $clause;

    public function __construct(Expression $clause)
    {
        parent::__construct(ExpressionType::NOT);

        $this->clause = $clause;
    }

    public function clause(): Expression
    {
        return $this->clause;
    }
}

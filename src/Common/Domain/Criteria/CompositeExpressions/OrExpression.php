<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions;

use olml89\PlayaMedia\Common\Domain\Criteria\Expression;

final class OrExpression extends CompositeExpression
{
    /**
     * @var Expression[]
     */
    private readonly array $clauses;

    public function __construct(Expression ...$clauses)
    {
        parent::__construct(ExpressionType::OR);

        $this->clauses = $clauses;
    }

    /**
     * @return Expression[]
     */
    public function clauses(): array
    {
        return $this->clauses;
    }
}

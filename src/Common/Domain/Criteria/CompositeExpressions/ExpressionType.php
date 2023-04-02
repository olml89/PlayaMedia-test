<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions;

enum ExpressionType: string
{
    case AND = 'AND';
    case OR = 'OR';
    case NOT = 'NOT';
}

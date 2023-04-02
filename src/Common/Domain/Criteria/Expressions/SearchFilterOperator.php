<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Criteria\Expressions;

enum SearchFilterOperator: string
{
    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case GT = '>';
    case GTE = '>=';
    case LT = '<';
    case LTE = '<=';
}

<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Infrastructure\Doctrine;

use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\Comparison as DoctrineComparison;
use Doctrine\Common\Collections\Expr\CompositeExpression as DoctrineCompositeExpression;
use Doctrine\Common\Collections\Expr\Expression as DoctrineExpression;
use olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions\CompositeExpression;
use olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions\ExpressionType;
use olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions\AndExpression;
use olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions\NotExpression;
use olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions\OrExpression;
use olml89\PlayaMedia\Common\Domain\Criteria\Criteria;
use olml89\PlayaMedia\Common\Domain\Criteria\Expression;
use olml89\PlayaMedia\Common\Domain\Criteria\Expressions\SearchFilter;

final class DoctrineCriteriaConverter
{
    private function __construct(
        private readonly Criteria $criteria,
    ) {}

    public static function convert(Criteria $criteria): DoctrineCriteria
    {
        $converter = new self($criteria);

        return $converter->toDoctrineCriteria();
    }

    private function toDoctrineCriteria(): DoctrineCriteria
    {
        return new DoctrineCriteria(
            expression: $this->buildDoctrineExpression($this->criteria->expression()),
            orderings: null,
            firstResult: null,
            maxResults: null,
        );
    }

    private function getCompositeExpressionType(CompositeExpression $expression): string
    {
        return match ($expression->type()) {
            ExpressionType::AND => DoctrineCompositeExpression::TYPE_AND,
            ExpressionType::OR => DoctrineCompositeExpression::TYPE_OR,
            ExpressionType::NOT => DoctrineCompositeExpression::TYPE_NOT,
        };
    }

    /**
     * @return Expression[]
     */
    private function getCompositeExpressionClauses(CompositeExpression $expression): array
    {
        if ($expression instanceof AndExpression || $expression instanceof OrExpression) {
            return $expression->clauses();
        }

        /** @var NotExpression $expression */
        return [$expression->clause()];
    }

    private function buildDoctrineExpression(?Expression $expression): ?DoctrineExpression
    {
        if ($expression instanceof CompositeExpression) {
            return new DoctrineCompositeExpression(
                type: $this->getCompositeExpressionType($expression),
                expressions: array_map(
                    fn(Expression $expression): DoctrineExpression => $this->buildDoctrineExpression($expression),
                    $this->getCompositeExpressionClauses($expression),
                ),
            );
        }

        if ($expression instanceof SearchFilter) {
            return new DoctrineComparison(
                field: (string)$expression->field(),
                op: $expression->operator()->value,
                value: $expression->value(),
            );
        }

        return null;
    }
}

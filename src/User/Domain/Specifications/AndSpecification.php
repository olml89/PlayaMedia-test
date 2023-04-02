<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Domain\Specifications;

use olml89\PlayaMedia\Common\Domain\Criteria\Expression;
use olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions\AndExpression;
use olml89\PlayaMedia\User\Domain\User;

final class AndSpecification implements Specification
{
    /**
     * @var Specification[]
     */
    private readonly array $specifications;

    public function __construct(Specification ...$specifications)
    {
        $this->specifications = $specifications;
    }

    public function isSatisfiedBy(User $user): bool
    {
        foreach ($this->specifications as $specification) {
            if (!$specification->isSatisfiedBy($user)) {
                return false;
            }
        }

        return true;
    }

    public function expression(): Expression
    {
        return new AndExpression(
            ...array_map(
                fn(Specification $specification): Expression => $specification->expression(),
                $this->specifications,
            )
        );
    }
}

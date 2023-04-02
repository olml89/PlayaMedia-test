<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Criteria;

use olml89\PlayaMedia\Common\Domain\Specifications\Specification;
use olml89\PlayaMedia\User\Domain\Specifications\AndSpecification;
use UnexpectedValueException;

final class CriteriaBuilder
{
    private ?Expression $expression = null;

    public function and(Specification ...$specifications): self
    {
        if (count($specifications) === 0) {
            return $this;
        }

        $andSpecification = new AndSpecification(...$specifications);
        $this->expression = $andSpecification->expression();

        return $this;
    }

    private function reset(): void
    {
        $this->expression = null;
    }

    public function build(): Criteria
    {
        $expression = $this->expression;
        $this->reset();

        return new Criteria($expression);
    }
}

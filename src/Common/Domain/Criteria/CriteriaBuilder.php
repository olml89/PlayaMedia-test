<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Criteria;

use olml89\PlayaMedia\Common\Domain\Criteria\Order\Order;
use olml89\PlayaMedia\Common\Domain\Specifications\Specification;
use olml89\PlayaMedia\User\Domain\Specifications\AndSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\NotSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\OrSpecification;

final class CriteriaBuilder
{
    private ?Expression $expression = null;
    private ?Order $order = null;
    private ?int $offset = null;
    private ?int $limit = null;

    public function and(Specification ...$specifications): self
    {
        if (count($specifications) === 0) {
            return $this;
        }

        $andSpecification = new AndSpecification(...$specifications);
        $this->expression = $andSpecification->expression();

        return $this;
    }

    public function or(Specification ...$specifications): self
    {
        if (count($specifications) === 0) {
            return $this;
        }

        $orSpecification = new OrSpecification(...$specifications);
        $this->expression = $orSpecification->expression();

        return $this;
    }

    public function not(Specification $specification): self
    {
        $notSpecification = new NotSpecification($specification);
        $this->expression = $notSpecification->expression();

        return $this;
    }

    public function order(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function offset(?int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function limit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    private function reset(): void
    {
        $this->expression = null;
        $this->order = null;
        $this->offset = null;
        $this->limit = null;
    }

    public function build(): Criteria
    {
        $expression = $this->expression;
        $order = $this->order;
        $offset = $this->offset;
        $limit = $this->limit;
        $this->reset();

        return new Criteria($expression, $order, $offset, $limit);
    }
}

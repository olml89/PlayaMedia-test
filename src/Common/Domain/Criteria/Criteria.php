<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Criteria;

use olml89\PlayaMedia\Common\Domain\Criteria\Order\Order;

final class Criteria
{
    public function __construct(
        private readonly ?Expression $expression,
        private readonly ?Order $order,
        private readonly ?int $offset,
        private readonly ?int $limit,
    ) {}

    public function expression(): ?Expression
    {
        return $this->expression;
    }

    public function order(): ?Order
    {
        return $this->order;
    }

    public function offset(): ?int
    {
        return $this->offset;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }
}

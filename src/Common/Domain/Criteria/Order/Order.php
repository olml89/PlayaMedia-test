<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Criteria\Order;

final class Order
{
    public function __construct(
        private readonly OrderBy $orderBy,
        private readonly OrderType $orderType,
    ) {}

    public function orderBy(): OrderBy
    {
        return $this->orderBy;
    }

    public function orderType(): OrderType
    {
        return $this->orderType;
    }
}

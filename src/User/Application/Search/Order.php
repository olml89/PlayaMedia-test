<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Application\Search;

use olml89\PlayaMedia\Common\Domain\Criteria\Order\OrderBy;
use olml89\PlayaMedia\Common\Domain\Criteria\Order\OrderType;
use olml89\PlayaMedia\Common\Domain\Criteria\Order\Order as CriteriaOrder;

final class Order
{
    public function __construct(
        public readonly ?string $order_by,
        public readonly ?string $order_type,
    ) {}

    public function order(): ?CriteriaOrder
    {
        if (is_null($this->order_by)) {
            return null;
        }

        return new CriteriaOrder(
            orderBy: new OrderBy($this->order_by),
            orderType: is_null($this->order_type)
                ? OrderType::ASC
                : OrderType::from($this->order_type),
        );
    }
}

<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Application\Search;

use olml89\PlayaMedia\Common\Domain\Criteria\Criteria;
use olml89\PlayaMedia\Common\Domain\Criteria\CriteriaBuilder;

final class SearchData
{
    public function __construct(
        public readonly Filters $filters,
        public readonly Order $order,
        public readonly ?int $offset,
        public readonly ?int $limit,
    ) {}
    
    public function criteria(CriteriaBuilder $criteriaBuilder): Criteria
    {
        return $criteriaBuilder
            ->and(...$this->filters->specifications())
            ->order($this->order->order())
            ->offset($this->offset)
            ->limit($this->limit)
            ->build();
    }
}

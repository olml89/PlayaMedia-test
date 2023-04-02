<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Domain;

use olml89\PlayaMedia\Common\Domain\Criteria\Criteria;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function all(): array;

    /**
     * @return User[]
     */
    public function search(Criteria $criteria): array;
}

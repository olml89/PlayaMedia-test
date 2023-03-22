<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Domain;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function all(): array;
}

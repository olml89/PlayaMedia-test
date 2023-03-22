<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Infrastructure\Persistence;

use olml89\PlayaMedia\User\Domain\User;
use olml89\PlayaMedia\User\Domain\UserRepository;
use olml89\PlayaMedia\User\Domain\UserType;

final class DummyUserRepository implements UserRepository
{
    /**
     * @return User[]
     */
    public function all(): array
    {
        return [
            new User(
                username: 'John Smith',
                email: 'email@email.com',
                password: '12345',
                isMember: true,
                isActive: true,
                userType:
                UserType::Type1,
            ),
        ];
    }
}

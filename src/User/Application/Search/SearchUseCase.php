<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Application\Search;

use olml89\PlayaMedia\User\Application\UserResult;
use olml89\PlayaMedia\User\Domain\User;
use olml89\PlayaMedia\User\Domain\UserRepository;

final class SearchUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    /**
     * @return UserResult[]
     */
    public function search(): array
    {
        return array_map(
            fn(User $user): UserResult => new UserResult($user),
            $this->userRepository->all()
        );
    }
}

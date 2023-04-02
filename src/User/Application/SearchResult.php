<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Application;

use olml89\PlayaMedia\Common\Domain\JsonSerializableObject;
use olml89\PlayaMedia\User\Domain\User;

final class SearchResult extends JsonSerializableObject
{
    public readonly int $count;

    /**
     * @var UserResult[]
     */
    public readonly array $users;

    public function __construct(User ...$users)
    {
        $this->count = count($users);

        $this->users = array_map(
            fn(User $user): UserResult => new UserResult($user),
            $users
        );
    }
}

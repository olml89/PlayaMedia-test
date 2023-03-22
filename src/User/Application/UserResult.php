<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Application;

use olml89\PlayaMedia\Common\Domain\JsonSerializableObject;
use olml89\PlayaMedia\User\Domain\User;

final class UserResult extends JsonSerializableObject
{
    public readonly int $id;
    public readonly string $username;
    public readonly string $email;
    public readonly bool $is_member;
    public readonly ?bool $is_active;
    public readonly int $user_type;
    public readonly ?string $last_login_at;
    public readonly string $created_at;
    public readonly string $updated_at;

    public function __construct(User $user)
    {
        $this->id = $user->id();
        $this->username = $user->username();
        $this->email = $user->email();
        $this->is_member = $user->isMember();
        $this->is_active = $user->isActive();
        $this->user_type = $user->userType()->value;
        $this->last_login_at = is_null($user->lastLoginAt())
            ? null
            : $user->lastLoginAt()->format('c');
        $this->created_at = $user->createdAt()->format('c');
        $this->updated_at = $user->updatedAt()->format('c');
    }
}

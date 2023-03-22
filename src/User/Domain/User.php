<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Domain;

use DateTimeImmutable;

class User
{
    private readonly int $id;
    private ?DateTimeImmutable $lastLoginAt;
    private readonly DateTimeImmutable $createdAt;
    private readonly DateTimeImmutable $updatedAt;

    public function __construct(
        private string $username,
        private string $email,
        private string $password,
        private bool $isMember,
        private ?bool $isActive,
        private UserType $userType,
    ) {
        $this->id = 0;
        $this->lastLoginAt = null;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): int
    {
        return $this->id;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isMember(): bool
    {
        return $this->isMember;
    }

    public function promote(): self
    {
        $this->isMember = true;

        return $this;
    }

    public function demote(): self
    {
        $this->isMember = false;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive ?? false;
    }

    public function activate(): self
    {
        $this->isActive = true;

        return $this;
    }

    public function deactivate(): self
    {
        $this->isActive = false;

        return $this;
    }

    public function userType(): UserType
    {
        return $this->userType;
    }

    public function setUserType(UserType $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    public function lastLoginAt(): ?DateTimeImmutable
    {
        return $this->lastLoginAt;
    }

    public function login(): self
    {
        $this->lastLoginAt = new DateTimeImmutable();

        return $this;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

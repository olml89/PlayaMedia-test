<?php declare(strict_types=1);

namespace Tests\User\Unit;

use DateTimeImmutable;
use olml89\PlayaMedia\Common\Domain\ValueObjects\DateTimeRangeValueObject;
use olml89\PlayaMedia\User\Domain\Specifications\AndSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\IsActiveSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\IsMemberSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\LastLoginAtSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\NotSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\OrSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\UserTypeSpecification;
use olml89\PlayaMedia\User\Domain\User;
use olml89\PlayaMedia\User\Domain\UserType;
use Tests\TestCase;

final class UserSpecificationsTest extends TestCase
{
    private readonly User $user;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->user = new User(
            username: 'username',
            email: 'user@email.com',
            password: 'secret',
            isMember: true,
            isActive: false,
            userType: UserType::Type1,
        );
    }

    public function test_is_active_specification(): void
    {
        $isActiveSpecification = new IsActiveSpecification($this->user->isActive());

        $this->assertTrue($isActiveSpecification->isSatisfiedBy($this->user));
    }

    public function test_is_member_specification(): void
    {
        $isMemberSpecification = new IsMemberSpecification($this->user->isMember());

        $this->assertTrue($isMemberSpecification->isSatisfiedBy($this->user));
    }

    public function test_user_type_specification(): void
    {
        $userTypeSpecification = new UserTypeSpecification($this->user->userType());

        $this->assertTrue($userTypeSpecification->isSatisfiedBy($this->user));
    }

    public function test_last_login_at_specification(): void
    {
        $lastLoginAtRange = new DateTimeRangeValueObject(
            start: (new DateTimeImmutable())->modify('-1 hour'),
            end: (new DateTimeImmutable())->modify('+1 hour'),
        );
        $lastLoginAtSpecification = new LastLoginAtSpecification($lastLoginAtRange);

        $this->user->login();

        $this->assertTrue($lastLoginAtSpecification->isSatisfiedBy($this->user));
    }

    public function test_not_specification(): void
    {
        $notSpecification = new NotSpecification(
            new IsMemberSpecification($this->user->isMember())
        );

        $this->assertFalse($notSpecification->isSatisfiedBy($this->user));
    }

    public function test_and_specification(): void
    {
        $andSpecification = new AndSpecification(
            new IsActiveSpecification(!$this->user->isActive()),
            new IsMemberSpecification($this->user->isMember()),
        );

        $this->assertFalse($andSpecification->isSatisfiedBy($this->user));
    }

    public function test_or_specification(): void
    {
        $orSpecification = new OrSpecification(
            new IsActiveSpecification(!$this->user->isActive()),
            new IsMemberSpecification($this->user->isMember()),
        );

        $this->assertTrue($orSpecification->isSatisfiedBy($this->user));
    }
}

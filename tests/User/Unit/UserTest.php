<?php declare(strict_types=1);

namespace Tests\User\Unit;

use DateTimeImmutable;
use olml89\PlayaMedia\User\Domain\User;
use olml89\PlayaMedia\User\Domain\UserType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\GetsKernelClass;

final class UserTest extends KernelTestCase
{
    use GetsKernelClass;

    private User $user;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->user = new User(
            username: 'username',
            email: 'user@email.com',
            password: 'secret',
            isMember: false,
            isActive: false,
            userType: UserType::Type1,
        );
    }

    public function test_creation_is_applied(): void
    {
        $this->assertEquals(
            (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            $this->user->createdAt()->format('Y-m-d H:i:s')
        );
    }

    public function test_update_is_applied(): void
    {
        $this->assertEquals(
            (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            $this->user->updatedAt()->format('Y-m-d H:i:s')
        );
    }

    public function test_username_is_changed(): void
    {
        $username = 'another_username';

        $this->user->setUsername($username);

        $this->assertEquals($username, $this->user->username());
    }

    public function test_email_is_changed(): void
    {
        $email = 'another.address@email.com';

        $this->user->setEmail($email);

        $this->assertEquals($email, $this->user->email());
    }

    public function test_password_is_changed(): void
    {
        $password = 'another_secret';

        $this->user->setPassword($password);

        $this->assertTrue($this->user->authenticate($password));
    }

    public function test_promotion_is_applied(): void
    {
        $this->user->promote();

        $this->assertTrue($this->user->isMember());
    }

    public function test_demotion_is_applied(): void
    {
        $this->user->demote();

        $this->assertFalse($this->user->isMember());
    }

    public function test_activation_is_applied(): void
    {
        $this->user->activate();

        $this->assertTrue($this->user->isActive());
    }

    public function test_deactivation_is_applied(): void
    {
        $this->user->deactivate();

        $this->assertFalse($this->user->isActive());
    }

    public function test_user_type_is_changed(): void
    {
        $userType = UserType::Type2;

        $this->user->setUserType($userType);

        $this->assertEquals($userType, $this->user->userType());
    }

    public function test_login_is_applied(): void
    {
        $this->user->login();

        $this->assertEquals(
            (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            $this->user->lastLoginAt()->format('Y-m-d H:i:s')
        );
    }
}

<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\PlayaMedia\User\Domain\User;
use olml89\PlayaMedia\User\Domain\UserRepository;

final class DoctrineUserRepository extends EntityRepository implements UserRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            new ClassMetadata(User::class)
        );
    }

    /**
     * @return User[]
     */
    public function all(): array
    {
        return $this->getEntityManager()->getRepository(User::class)->findAll();
    }
}

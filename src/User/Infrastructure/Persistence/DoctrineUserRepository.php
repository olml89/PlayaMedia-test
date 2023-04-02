<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Infrastructure\Persistence;

use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\PlayaMedia\Common\Domain\Criteria\Criteria;
use olml89\PlayaMedia\Common\Infrastructure\Doctrine\DoctrineCriteriaConverter;
use olml89\PlayaMedia\User\Domain\User;
use olml89\PlayaMedia\User\Domain\UserRepository;
use olml89\PlayaMedia\User\Domain\UserType;

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

    /**
     * @return User[]
     */
    public function search(Criteria $criteria): array
    {
        $doctrineCriteria = DoctrineCriteriaConverter::convert(
            criteria: $criteria,
            criteriaToDoctrineFields: $this->getEntityManager()->getClassMetadata(User::class)->fieldNames,
        );

        return $this->getEntityManager()->getRepository(User::class)->matching($doctrineCriteria)->toArray();
    }
}

<?php declare(strict_types=1);

namespace Tests\User\Integration;

use DateTimeImmutable;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\PlayaMedia\Common\Domain\Criteria\Order\OrderType;
use olml89\PlayaMedia\Common\Domain\ValueObjects\DateTimeRangeValueObject;
use olml89\PlayaMedia\Common\Domain\ValueObjects\NullableBoolValueObject;
use olml89\PlayaMedia\User\Application\Search\Filters;
use olml89\PlayaMedia\User\Application\Search\Order;
use olml89\PlayaMedia\User\Application\Search\SearchData;
use olml89\PlayaMedia\User\Application\Search\SearchUseCase;
use olml89\PlayaMedia\User\Application\SearchResult;
use olml89\PlayaMedia\User\Domain\User;
use olml89\PlayaMedia\User\Domain\UserRepository;
use olml89\PlayaMedia\User\Domain\UserType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\GetsKernelClass;
use Tests\InitsDatabase;

final class SearchUserUseCaseTest extends KernelTestCase
{
    use GetsKernelClass;

    private readonly SearchUseCase $searchUseCase;
    private readonly UserRepository $userRepository;
    private readonly EntityRepository $doctrineUserRepository;
    private readonly ClassMetadata $doctrineUserClassMetadata;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();

        $this->searchUseCase = self::getContainer()->get(SearchUseCase::class);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->doctrineUserRepository = $entityManager->getRepository(User::class);
        $this->doctrineUserClassMetadata = $entityManager->getClassMetadata(User::class);
    }

    private function createSearchData(
        Filters $filters = null,
        Order $order = null,
        int $offset = null,
        int $limit = null,
    ): SearchData
    {
        return new SearchData(
            filters: $filters ?? $this->createFilters(),
            order: $order ?? new Order(null, null),
            offset: $offset,
            limit: $limit,
        );
    }

    private function createFilters(
        NullableBoolValueObject $is_active = null,
        bool $is_member = null,
        UserType $user_type = null,
        DateTimeRangeValueObject $last_login_at = null,
    ): Filters
    {
        return new Filters(
            is_active: $is_active,
            is_member: $is_member,
            user_type: $user_type,
            last_login_at: $last_login_at,
        );
    }

    private function getRandomTimestamp(DateTimeImmutable $firstDateTime, DateTimeImmutable $secondDateTime): int
    {
        return mt_rand($firstDateTime->getTimestamp(), $secondDateTime->getTimestamp());
    }

    private function createDateTimeRangeValueObject(): DateTimeRangeValueObject
    {
        /** @var User[] $users */
        $users = $this->doctrineUserRepository->matching(
            DoctrineCriteria::create()
                ->where(DoctrineCriteria::expr()->neq('lastLoginAt', null))
                ->orderBy(['lastLoginAt' => OrderType::ASC->value])
        )->toArray();

        $earlierLastLoginAt = $users[0]->lastLoginAt();
        $laterLastLoginAt = $users[count($users) - 1]->lastLoginAt();
        $randomFirstTimestamp = $this->getRandomTimestamp($earlierLastLoginAt, $laterLastLoginAt);

        while (
            ($randomSecondTimestamp = $this->getRandomTimestamp($earlierLastLoginAt, $laterLastLoginAt)) <= $randomFirstTimestamp
        )
        {
        }

        return new DateTimeRangeValueObject(
            start: mt_rand(0, 1) === 0 ? $earlierLastLoginAt->setTimestamp($randomFirstTimestamp) : null,
            end: mt_rand(0, 1) === 0 ? $laterLastLoginAt->setTimestamp($randomSecondTimestamp) : null,
        );
    }

    /**
     * @param User[] $users
     */
    private function assertSearchResult(array $users, SearchResult $searchResult): void
    {
        $count = count($users);
        $this->assertEquals($count, $searchResult->count);

        for($i = 0; $i < $count; ++$i) {
            $this->assertEquals($users[$i]->id(), $searchResult->users[$i]->id);
        }
    }

    public function test_that_limit_criteria_is_applied(): void
    {
        $limit = mt_rand(20, 100);

        /** @var User[] $users */
        $users = $this->doctrineUserRepository->findBy(
            criteria: [],
            limit: $limit,
        );
        $searchData = $this->createSearchData(limit: $limit);

        $searchResult = $this->searchUseCase->search($searchData);

        $this->assertSearchResult($users, $searchResult);
    }

    public function test_that_limit_and_offset_criteria_are_applied(): void
    {
        $limit = mt_rand(20, 100);
        $offset = mt_rand(0, $this->doctrineUserRepository->count([]) - $limit);

        /** @var User[] $users */
        $users = $this->doctrineUserRepository->findBy(
            criteria: [],
            limit: $limit,
            offset: $offset,
        );

        $searchData = $this->createSearchData(
            offset: $offset,
            limit: $limit,
        );

        $searchResult = $this->searchUseCase->search($searchData);

        $this->assertSearchResult($users, $searchResult);
    }

    public function test_order_criteria_is_applied_with_default_order_type(): void
    {
        $databaseField = array_rand($this->doctrineUserClassMetadata->fieldNames);
        $doctrineField = $this->doctrineUserClassMetadata->fieldNames[$databaseField];

        /** @var User[] $users */
        $users = $this->doctrineUserRepository->findBy(
            criteria: [],
            orderBy: [$doctrineField => 'ASC'],
        );

        $searchData = $this->createSearchData(
            order: new Order($databaseField, null),
        );

        $searchResult = $this->searchUseCase->search($searchData);

        $this->assertSearchResult($users, $searchResult);
    }

    public function test_order_criteria_is_applied_with_doctrine_field_order_by(): void
    {
        $doctrineField = $this->doctrineUserClassMetadata->fieldNames[
            array_rand($this->doctrineUserClassMetadata->fieldNames)
        ];

        /** @var User[] $users */
        $users = $this->doctrineUserRepository->findBy(
            criteria: [],
            orderBy: [$doctrineField => 'ASC'],
        );

        $searchData = $this->createSearchData(
            order: new Order($doctrineField, null),
        );

        $searchResult = $this->searchUseCase->search($searchData);

        $this->assertSearchResult($users, $searchResult);
    }

    public function test_order_criteria_is_applied_with_requested_order_type_and_order_by(): void
    {
        $databaseField = array_rand($this->doctrineUserClassMetadata->fieldNames);
        $doctrineField = $this->doctrineUserClassMetadata->fieldNames[$databaseField];
        $orderType = OrderType::cases()[array_rand(OrderType::cases())];

        /** @var User[] $users */
        $users = $this->doctrineUserRepository->findBy(
            criteria: [],
            orderBy: [$doctrineField => $orderType->value],
        );

        $searchData = $this->createSearchData(
            order: new Order($databaseField, $orderType->value),
        );

        $searchResult = $this->searchUseCase->search($searchData);

        $this->assertSearchResult($users, $searchResult);
    }

    public function test_search_without_filters_is_the_same_as_listing_all_users(): void
    {
        $users = $this->userRepository->all();
        $searchData = $this->createSearchData();

        $searchResult = $this->searchUseCase->search($searchData);

        $this->assertSearchResult($users, $searchResult);
    }

    public function test_is_active_filter_is_applied(): void
    {
        $isActiveValues = [null, true, false];

        foreach ($isActiveValues as $isActive) {
            /** @var User[] $users */
            $users = $this->doctrineUserRepository->findBy(
                criteria: ['isActive' => $isActive],
            );

            $searchData = $this->createSearchData(
                filters: $this->createFilters(is_active: new NullableBoolValueObject($isActive)),
            );

            $searchResult = $this->searchUseCase->search($searchData);

            $this->assertSearchResult($users, $searchResult);

            foreach ($searchResult->users as $userResult) {
                $this->assertEquals($isActive, $userResult->is_active);
            }
        }
    }

    public function test_is_member_filter_is_applied(): void
    {
        $isMemberValues = [true, false];

        foreach ($isMemberValues as $isMember) {
            /** @var User[] $users */
            $users = $this->doctrineUserRepository->findBy(
                criteria: ['isMember' => $isMember],
            );

            $searchData = $this->createSearchData(
                filters: $this->createFilters(is_member: $isMember),
            );

            $searchResult = $this->searchUseCase->search($searchData);

            $this->assertSearchResult($users, $searchResult);

            foreach ($searchResult->users as $userResult) {
                $this->assertEquals($isMember, $userResult->is_member);
            }
        }
    }

    public function test_user_type_filter_is_applied(): void
    {
        $userTypeValues = UserType::cases();

        foreach ($userTypeValues as $userType) {
            /** @var User[] $users */
            $users = $this->doctrineUserRepository->findBy(
                criteria: ['userType' => $userType],
            );

            $searchData = $this->createSearchData(
                filters: $this->createFilters(user_type: $userType),
            );

            $searchResult = $this->searchUseCase->search($searchData);

            $this->assertSearchResult($users, $searchResult);

            foreach ($searchResult->users as $userResult) {
                $this->assertEquals($userType->value, $userResult->user_type);
            }
        }
    }

    public function test_last_login_at_filter_is_applied(): void
    {
        $lastLoginAt = $this->createDateTimeRangeValueObject();
        $doctrineCriteria = DoctrineCriteria::create();

        if (!is_null($lastLoginAt->start)) {
            $doctrineCriteria->andWhere(DoctrineCriteria::expr()->gte('lastLoginAt', $lastLoginAt->start));
        }

        if (!is_null($lastLoginAt->end)) {
            $doctrineCriteria->andWhere(DoctrineCriteria::expr()->lte('lastLoginAt', $lastLoginAt->end));
        }

        /** @var User[] $users */
        $users = $this->doctrineUserRepository->matching($doctrineCriteria)->toArray();

        $searchData = $this->createSearchData(
            filters: $this->createFilters(last_login_at: $lastLoginAt),
        );

        $searchResult = $this->searchUseCase->search($searchData);

        $this->assertSearchResult($users, $searchResult);
    }
}

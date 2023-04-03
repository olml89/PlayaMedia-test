<?php declare(strict_types=1);

namespace Tests\User\Unit;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\PlayaMedia\User\Domain\User;
use olml89\PlayaMedia\User\Infrastructure\Endpoints\SearchRequest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Tests\GetsKernelClass;

final class SearchRequestTest extends KernelTestCase
{
    use GetsKernelClass;

    private readonly ClassMetadata $userClassMetadata;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $this->userClassMetadata = $entityManager->getClassMetadata(User::class);
    }

    public function test_throws_bad_request_exception_if_unknown_parameter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?unknown=true');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_is_active_filter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?filters[is_active]=string');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_is_member_filter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?filters[is_member]=string');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_type_user_type_filter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?filters[user_type]=string');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_value_user_type_filter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?filters[user_type]=0');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_type_last_login_at_start_filter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?filters[last_login_at][start]=0');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_value_last_login_at_start_filter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?filters[last_login_at][start]=string');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_type_last_login_at_end_filter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?filters[last_login_at][end]=0');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_value_last_login_at_end_filter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?filters[last_login_at][end]=string');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_typecasts_query_string_filters(): void
    {
        $is_active = true;
        $is_member = false;
        $user_type = 3;
        $last_login_at_start = null;
        $last_login_at_end = '2022-05-12';

        $uri = sprintf(
            'http://localhost/api?%s&%s&%s&%s&%s',
            'filters[is_active]='.json_encode($is_active),
            'filters[is_member]='.json_encode($is_member),
            'filters[user_type]='.json_encode($user_type),
            'filters[last_login_at][start]='.json_encode($last_login_at_start),
            'filters[last_login_at][end]='.$last_login_at_end,
        );
        $searchRequest = SearchRequest::create(uri: $uri);

        $searchData = $searchRequest->validate($this->userClassMetadata);

        $this->assertEquals($is_active, $searchData->filters->is_active->toBool());
        $this->assertEquals($is_member, $searchData->filters->is_member);
        $this->assertEquals($user_type, $searchData->filters->user_type->value);
        $this->assertEquals($last_login_at_start, $searchData->filters->last_login_at->start);
        $this->assertEquals($last_login_at_end, $searchData->filters->last_login_at->end->format('Y-m-d'));
    }

    public function test_throws_bad_request_exception_if_incorrect_type_offset_parameter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?offset=string');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_value_offset_parameter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?offset=0');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_type_limit_parameter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?limit=string');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_value_limit_parameter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?limit=0');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_type_order_by_parameter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?order[by]=0');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_value_order_by_parameter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?order[by]=string');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_type_order_type_parameter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?order[type]=0');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }

    public function test_throws_bad_request_exception_if_incorrect_value_order_type_parameter_is_provided(): void
    {
        $searchRequest = SearchRequest::create(uri: 'http://localhost/api?order[type]=string');

        $this->expectException(BadRequestException::class);

        $searchRequest->validate($this->userClassMetadata);
    }
}

<?php declare(strict_types=1);

namespace Tests\User\Application;

use olml89\PlayaMedia\User\Domain\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\GetsKernelClass;

final class SearchUserFeatureTest extends WebTestCase
{
    use GetsKernelClass;

    private readonly KernelBrowser $client;
    private readonly UserRepository $userRepository;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->userRepository = self::getContainer()->get(UserRepository::class);
    }

    private function getJsonResponse(string $method, string $uri): Response
    {
        $this->client->request($method, $uri);
        return $this->client->getResponse();
    }

    public function test_request_without_filters_returns_complete_list_of_users(): void
    {
        $users = $this->userRepository->all();
        $response = $this->getJsonResponse('GET', '/api/users');
        $responseData = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
        $this->assertEquals(count($users), $responseData['count']);
        $this->assertCount(count($users), $responseData['users']);

        foreach ($responseData['users'] as $index => $userData) {
            $this->assertEquals($users[$index]->id(), $userData['id']);
        }
    }
}

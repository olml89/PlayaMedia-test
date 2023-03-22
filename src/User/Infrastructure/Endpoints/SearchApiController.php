<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Infrastructure\Endpoints;

use olml89\PlayaMedia\User\Application\Search\SearchUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class SearchApiController extends AbstractController
{
    public function __construct(
        private readonly SearchUseCase $searchUseCase,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $usersResults = $this->searchUseCase->search();

        return $this->json($usersResults);
    }
}

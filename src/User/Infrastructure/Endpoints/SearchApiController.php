<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Infrastructure\Endpoints;

use olml89\PlayaMedia\User\Application\Search\SearchUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class SearchApiController extends AbstractController
{
    public function __construct(
        private readonly SearchUseCase $searchUseCase,
    ) {}

    public function __invoke(SearchRequest $request): JsonResponse
    {
        $searchData = $request->validate();
        $searchResult = $this->searchUseCase->search($searchData);

        return $this->json($searchResult);
    }
}

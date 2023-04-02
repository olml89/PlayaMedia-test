<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Infrastructure\Endpoints;

use Doctrine\ORM\EntityManagerInterface;
use olml89\PlayaMedia\User\Application\Search\SearchUseCase;
use olml89\PlayaMedia\User\Domain\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class SearchApiController extends AbstractController
{
    public function __construct(
        private readonly SearchUseCase $searchUseCase,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(SearchRequest $request): JsonResponse
    {
        $searchData = $request->validate($this->entityManager->getClassMetadata(User::class));
        $searchResult = $this->searchUseCase->search($searchData);

        return $this->json($searchResult);
    }
}

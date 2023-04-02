<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Application\Search;

use olml89\PlayaMedia\Common\Domain\Criteria\CriteriaBuilder;
use olml89\PlayaMedia\User\Application\SearchResult;
use olml89\PlayaMedia\User\Domain\UserRepository;

final class SearchUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CriteriaBuilder $criteriaBuilder,
    ) {}

    public function search(SearchData $searchData): SearchResult
    {
        $criteria = $searchData->criteria($this->criteriaBuilder);

        return new SearchResult(...$this->userRepository->search($criteria));
    }
}

<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Application\Search;

use olml89\PlayaMedia\Common\Domain\Criteria\Criteria;
use olml89\PlayaMedia\Common\Domain\Criteria\CriteriaBuilder;
use olml89\PlayaMedia\Common\Domain\ValueObjects\DateTimeRangeValueObject;
use olml89\PlayaMedia\Common\Domain\ValueObjects\NullableBoolValueObject;
use olml89\PlayaMedia\User\Domain\Specifications\IsActiveSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\IsMemberSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\LastLoginAtSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\UserTypeSpecification;
use olml89\PlayaMedia\User\Domain\UserType;

final class SearchData
{
    public function __construct(
        public readonly ?NullableBoolValueObject $is_active,
        public readonly ?bool $is_member,
        public readonly ?UserType $user_type,
        public readonly ?DateTimeRangeValueObject $last_login_at,
    ) {}
    
    public function criteria(CriteriaBuilder $criteriaBuilder): Criteria
    {
        $specifications = [];

        if (!is_null($this->is_active)) {
            $specifications[] = new IsActiveSpecification($this->is_active->toBool());
        }

        if (!is_null($this->is_member)) {
            $specifications[] = new IsMemberSpecification($this->is_member);
        }

        if (!is_null($this->user_type)) {
            $specifications[] = new UserTypeSpecification($this->user_type);
        }

        if (!is_null($this->last_login_at)) {
            $specifications[] = new LastLoginAtSpecification($this->last_login_at);
        }

        return $criteriaBuilder->and(...$specifications)->build();
    }
}

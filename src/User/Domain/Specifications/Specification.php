<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Domain\Specifications;

use olml89\PlayaMedia\Common\Domain\Specifications\Specification as GenericSpecification;
use olml89\PlayaMedia\User\Domain\User;

interface Specification extends GenericSpecification
{
    public function isSatisfiedBy(User $user): bool;
}

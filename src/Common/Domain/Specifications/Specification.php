<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Domain\Specifications;

use olml89\PlayaMedia\Common\Domain\Criteria\Expression;

interface Specification
{
    public function expression(): Expression;
}

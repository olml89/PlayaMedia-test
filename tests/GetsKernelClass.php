<?php declare(strict_types=1);

namespace Tests;

use olml89\PlayaMedia\Common\Infrastructure\Symfony\Kernel;

trait GetsKernelClass
{
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }
}

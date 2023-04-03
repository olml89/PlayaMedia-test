<?php declare(strict_types=1);

namespace Tests;

use olml89\PlayaMedia\Common\Infrastructure\Symfony\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class TestCase extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }
}

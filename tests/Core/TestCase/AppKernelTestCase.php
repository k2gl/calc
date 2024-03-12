<?php

declare(strict_types=1);

namespace AppTests\Core\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;

abstract class AppKernelTestCase extends KernelTestCase
{
    use Factories;
}

<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Symfony\DependencyInjeciton;

use Dumplie\SharedKernel\Application\ServiceLocator as Locator;
use Symfony\Component\DependencyInjection\Container;

class ServiceLocator extends Container implements Locator
{
}
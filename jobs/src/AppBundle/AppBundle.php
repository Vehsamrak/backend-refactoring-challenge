<?php

declare(strict_types=1);

namespace AppBundle;

use AppBundle\DependencyInjection\AppExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AppBundle extends Bundle
{
    public function getContainerExtension(): Extension
    {
        return new AppExtension();
    }
}

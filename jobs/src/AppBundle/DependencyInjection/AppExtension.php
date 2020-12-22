<?php

declare(strict_types=1);

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class AppExtension extends Extension
{
    private const CONFIG_DIR = __DIR__.'/../Resources/config';
    private const CONFIG_FILES = [
        'services.yml',
    ];

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(self::CONFIG_DIR));
        foreach (self::CONFIG_FILES as $configFile) {
            $loader->load($configFile);
        }
    }
}

<?php

declare(strict_types=1);

namespace AL\PhpWndb\DI;

use Psr\Container\ContainerInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DiContainerFactory
{
    private const CONTAINER_NAME = 'PhpWndbContainer';

    public function create(): ContainerInterface
    {
        return $this->createCompiledContainerBuilder();
    }

    public function createCached(string $cacheDir, bool $isDebug): ContainerInterface
    {
        $filepath = $cacheDir . DIRECTORY_SEPARATOR . self::CONTAINER_NAME . '.php';
        $containerConfigCache = new ConfigCache($filepath, $isDebug);

        if (! $containerConfigCache->isFresh()) {
            $containerBuilder = $this->createCompiledContainerBuilder();

            $phpDumper = new PhpDumper($containerBuilder);
            $code = $phpDumper->dump(['class' => self::CONTAINER_NAME]);
            \assert(\is_string($code));

            $containerConfigCache->write($code, $containerBuilder->getResources());
        }

        require $filepath;

        $containerClass = self::CONTAINER_NAME;
        \class_exists($containerClass) || throw new \LogicException("`{$containerClass}` not found.");

        return new $containerClass();
    }

    protected function createCompiledContainerBuilder(): ContainerBuilder
    {
        $builder = new ContainerBuilder();
        $builder->setParameter('rootDir', __DIR__ . '/../../');

        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../../config/'));
        $loader->load('config.yaml');

        $builder->compile();

        return $builder;
    }
}

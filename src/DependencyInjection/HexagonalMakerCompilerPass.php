<?php

namespace AdrienLbt\HexagonalMakerBundle\DependencyInjection;

use AdrienLbt\HexagonalMakerBundle\Generator\HexagonalGenerator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass to decorate Symfony MakerBundle's Generator
 *
 * This replaces the PSR-4 autoload override mechanism with a proper
 * Symfony decorator pattern, making the bundle more maintainable and
 * compatible with future Symfony versions.
 */
class HexagonalMakerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // Only proceed if the maker.generator service exists
        if (!$container->hasDefinition('maker.generator')) {
            return;
        }

        // Register our custom generator as a decorator
        $container->register('hexagonal_maker.generator', HexagonalGenerator::class)
            ->setDecoratedService('maker.generator')
            ->setArguments([
                new Reference('hexagonal_maker.generator.inner'),
                '%hexagonal_maker.domain_path%',
            ])
            ->setPublic(false);
    }
}

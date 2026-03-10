<?php

namespace AdrienLbt\HexagonalMakerBundle\Tests\DependencyInjection;

use AdrienLbt\HexagonalMakerBundle\DependencyInjection\HexagonalMakerCompilerPass;
use AdrienLbt\HexagonalMakerBundle\Generator\HexagonalGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Test for HexagonalMakerCompilerPass
 */
class HexagonalMakerCompilerPassTest extends TestCase
{
    public function testProcessRegistersGeneratorDecorator(): void
    {
        $container = new ContainerBuilder();

        // Set the domain path parameter
        $container->setParameter('hexagonal_maker.domain_path', 'Domain');

        // Register the maker.generator service (simulating MakerBundle)
        $makerGeneratorDefinition = new Definition();
        $container->setDefinition('maker.generator', $makerGeneratorDefinition);

        // Process the compiler pass
        $compilerPass = new HexagonalMakerCompilerPass();
        $compilerPass->process($container);

        // Verify that our decorator is registered
        $this->assertTrue(
            $container->hasDefinition('hexagonal_maker.generator'),
            'The hexagonal_maker.generator service should be registered'
        );

        $decoratorDefinition = $container->getDefinition('hexagonal_maker.generator');

        // Verify the class
        $this->assertEquals(
            HexagonalGenerator::class,
            $decoratorDefinition->getClass(),
            'The decorator should use HexagonalGenerator class'
        );

        // Verify it's decorated
        $this->assertEquals(
            'maker.generator',
            $decoratorDefinition->getDecoratedService()[0],
            'The service should decorate maker.generator'
        );

        // Verify it's not public
        $this->assertFalse(
            $decoratorDefinition->isPublic(),
            'The decorator should not be public'
        );
    }

    public function testProcessDoesNothingWhenMakerGeneratorNotPresent(): void
    {
        $container = new ContainerBuilder();

        // Do NOT register maker.generator service

        $compilerPass = new HexagonalMakerCompilerPass();
        $compilerPass->process($container);

        // Verify that our decorator is NOT registered
        $this->assertFalse(
            $container->hasDefinition('hexagonal_maker.generator'),
            'The hexagonal_maker.generator should not be registered if maker.generator does not exist'
        );
    }
}

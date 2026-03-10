<?php

namespace AdrienLbt\HexagonalMakerBundle\Tests\Generator;

use AdrienLbt\HexagonalMakerBundle\Generator\HexagonalGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

/**
 * Test for HexagonalGenerator
 */
class HexagonalGeneratorTest extends TestCase
{
    private HexagonalGenerator $generator;
    private Generator $decoratedGenerator;

    protected function setUp(): void
    {
        // Mock the decorated generator
        $this->decoratedGenerator = $this->createMock(Generator::class);

        // Create the hexagonal generator
        $this->generator = new HexagonalGenerator($this->decoratedGenerator, 'Domain');
    }

    public function testGeneratorCanBeInstantiated(): void
    {
        $this->assertInstanceOf(HexagonalGenerator::class, $this->generator);
    }

    public function testCreateClassNameDetailsWithNonDomainNamespace(): void
    {
        $expectedClassNameDetails = new ClassNameDetails(
            'App\\Controller\\UserController',
            'App\\Controller',
            'Controller'
        );

        // For non-Domain namespaces, delegate to decorated generator
        $this->decoratedGenerator
            ->expects($this->once())
            ->method('createClassNameDetails')
            ->with('User', 'Controller', 'Controller', '')
            ->willReturn($expectedClassNameDetails);

        $result = $this->generator->createClassNameDetails(
            'User',
            'Controller',
            'Controller'
        );

        $this->assertSame($expectedClassNameDetails, $result);
    }

    public function testDelegateGetRootDirectory(): void
    {
        $this->decoratedGenerator
            ->expects($this->once())
            ->method('getRootDirectory')
            ->willReturn('/path/to/project');

        $result = $this->generator->getRootDirectory();

        $this->assertEquals('/path/to/project', $result);
    }

    public function testDelegateWriteChanges(): void
    {
        $this->decoratedGenerator
            ->expects($this->once())
            ->method('writeChanges');

        $this->generator->writeChanges();
    }

    public function testDelegateGetGeneratedFiles(): void
    {
        $expectedFiles = ['src/Domain/UseCase/CreateUser.php'];

        $this->decoratedGenerator
            ->expects($this->once())
            ->method('getGeneratedFiles')
            ->willReturn($expectedFiles);

        $result = $this->generator->getGeneratedFiles();

        $this->assertEquals($expectedFiles, $result);
    }

    public function testDelegateHasPendingOperations(): void
    {
        $this->decoratedGenerator
            ->expects($this->once())
            ->method('hasPendingOperations')
            ->willReturn(true);

        $result = $this->generator->hasPendingOperations();

        $this->assertTrue($result);
    }

    public function testDelegateGetRootNamespace(): void
    {
        $this->decoratedGenerator
            ->expects($this->once())
            ->method('getRootNamespace')
            ->willReturn('App');

        $result = $this->generator->getRootNamespace();

        $this->assertEquals('App', $result);
    }
}

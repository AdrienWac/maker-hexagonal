<?php

namespace AdrienLbt\HexagonalMakerBundle\Tests\Maker;

use AdrienLbt\HexagonalMakerBundle\Maker\MakeHexagonalUseCase;
use PHPUnit\Framework\TestCase;

/**
 * Basic test for MakeHexagonalUseCase
 *
 * Integration tests with MakerTestCase are skipped as they require
 * a full Symfony application context with the bundle properly configured.
 */
class MakeHexagonalUseCaseTest extends TestCase
{
    public function testMakerCanBeInstantiated(): void
    {
        $maker = new MakeHexagonalUseCase('Domain');

        $this->assertInstanceOf(MakeHexagonalUseCase::class, $maker);
        $this->assertEquals('make:hexagonal:usecase', MakeHexagonalUseCase::getCommandName());
        $this->assertStringContainsString('use case', MakeHexagonalUseCase::getCommandDescription());
    }
}

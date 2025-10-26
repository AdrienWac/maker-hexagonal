<?php
declare(strict_types=1);

namespace AdrienLbt\HexagonalMakerBundle\Tests\Unit\Maker\Utils\ClassManipulator;

use AdrienLbt\HexagonalMakerBundle\Maker\Utils\ClassManipulator\ProxyClassSourceManipulator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\MakerBundle\Util\ClassSourceManipulator;

class ProxyClassSourceManipulatorTest extends TestCase
{

    /**
     *
     * @return void
     */
    public function testCallMethodSetVisibility(): void
    {
        /** @var MockObject|ClassSourceManipulator $classSourceManipulatorMock */
        $classSourceManipulatorMock = $this
            ->getMockBuilder(ClassSourceManipulator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $classSourceManipulatorMock
            ->expects($this->once())
            ->method('buildNodeExprByValue');


        $proxyClassSourceManipulator = new ProxyClassSourceManipulator($classSourceManipulatorMock);

    }

}
<?php

declare(strict_types=1);

namespace Symplify\AutowireArrayParameter\Tests\DependencyInjection\CompilerPass;

use Symplify\AutowireArrayParameter\Tests\DependencyInjection\CompilerPass\Source\ArrayShapeCollector;
use Symplify\AutowireArrayParameter\Tests\DependencyInjection\CompilerPass\Source\Contract\FirstCollectedInterface;
use Symplify\AutowireArrayParameter\Tests\DependencyInjection\CompilerPass\Source\Contract\SecondCollectedInterface;
use Symplify\AutowireArrayParameter\Tests\DependencyInjection\CompilerPass\Source\IterableCollector;
use Symplify\AutowireArrayParameter\Tests\DependencyInjection\CompilerPass\Source\SomeCollector;
use Symplify\AutowireArrayParameter\Tests\HttpKernel\AutowireArrayParameterHttpKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class AutowireArrayParameterCompilerPassTest extends AbstractKernelTestCase
{
    protected function setUp(): void
    {
        $this->bootKernel(AutowireArrayParameterHttpKernel::class);
    }

    public function test(): void
    {
        /** @var SomeCollector $someCollector */
        $someCollector = $this->getService(SomeCollector::class);
        $this->assertCount(3, $someCollector->getFirstCollected());
        $this->assertCount(2, $someCollector->getSecondCollected());

        $this->assertInstanceOf(FirstCollectedInterface::class, $someCollector->getFirstCollected()[0]);
        $this->assertInstanceOf(SecondCollectedInterface::class, $someCollector->getSecondCollected()[0]);
    }

    public function testArrayShape(): void
    {
        $arrayShapeCollector = $this->getService(ArrayShapeCollector::class);
        $this->assertCount(3, $arrayShapeCollector->getFirstCollected());
        $this->assertCount(2, $arrayShapeCollector->getSecondCollected());

        $this->assertInstanceOf(FirstCollectedInterface::class, $arrayShapeCollector->getFirstCollected()[0]);
        $this->assertInstanceOf(SecondCollectedInterface::class, $arrayShapeCollector->getSecondCollected()[0]);
    }

    public function testIterable(): void
    {
        $iterableCollector = $this->getService(IterableCollector::class);

        $this->assertCount(3, $iterableCollector->getFirstCollected());
        $this->assertCount(2, $iterableCollector->getSecondCollected());

        $this->assertInstanceOf(FirstCollectedInterface::class, $iterableCollector->getFirstCollected()[0]);
        $this->assertInstanceOf(SecondCollectedInterface::class, $iterableCollector->getSecondCollected()[0]);
    }
}

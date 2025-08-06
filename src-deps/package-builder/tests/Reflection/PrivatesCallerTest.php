<?php

declare(strict_types=1);

namespace Symplify\PackageBuilder\Tests\Reflection;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symplify\PackageBuilder\Reflection\PrivatesCaller;
use Symplify\PackageBuilder\Tests\Reflection\Source\SomeClassWithPrivateMethods;

final class PrivatesCallerTest extends TestCase
{
    private PrivatesCaller $privatesCaller;

    protected function setUp(): void
    {
        $this->privatesCaller = new PrivatesCaller();
    }

    /**
     * @param mixed[]|int[] $arguments
     */
    #[DataProvider('provideData')]
    public function test(
        string | SomeClassWithPrivateMethods $object,
        string $methodName,
        array $arguments,
        int $expectedResult
    ): void {
        $result = $this->privatesCaller->callPrivateMethod($object, $methodName, $arguments);
        $this->assertSame($expectedResult, $result);
    }

    public static function provideData(): Iterator
    {
        yield [SomeClassWithPrivateMethods::class, 'getNumber', [], 5];
        yield [new SomeClassWithPrivateMethods(), 'getNumber', [], 5];
        yield [new SomeClassWithPrivateMethods(), 'plus10', [30], 40];
    }

    #[DataProvider('provideDataReference')]
    public function testReference(
        SomeClassWithPrivateMethods $someClassWithPrivateMethods,
        string $methodName,
        int $referencedArgument,
        int $expectedResult
    ): void {
        $result = $this->privatesCaller->callPrivateMethodWithReference(
            $someClassWithPrivateMethods,
            $methodName,
            $referencedArgument
        );
        $this->assertSame($expectedResult, $result);
    }

    public static function provideDataReference(): Iterator
    {
        yield [new SomeClassWithPrivateMethods(), 'multipleByTwo', 10, 20];
    }
}

<?php

declare(strict_types=1);

namespace Symplify\AutowireArrayParameter\Tests\DocBlock;

use Iterator;
use PHPUnit\Framework\TestCase;
use Symplify\AutowireArrayParameter\DocBlock\ParamTypeDocBlockResolver;

final class ParamTypeDocBlockResolverTest extends TestCase
{
    private ParamTypeDocBlockResolver $paramTypeDocBlockResolver;

    protected function setUp(): void
    {
        $this->paramTypeDocBlockResolver = new ParamTypeDocBlockResolver();
    }

    /**
     * @dataProvider provideData()
     */
    public function test(string $docBlock, string $parameterName, string $expectedType): void
    {
        $resolvedType = $this->paramTypeDocBlockResolver->resolve($docBlock, $parameterName);
        $this->assertSame($expectedType, $resolvedType);
    }

    public static function provideData(): Iterator
    {
        yield ['/** @param Type[] $name */', 'name', 'Type'];
        yield ['/** @param array<Type> $name */', 'name', 'Type'];
        yield ['/** @param iterable<Type> $name */', 'name', 'Type'];
    }

    /**
     * @dataProvider provideDataMissmatchName()
     */
    public function testMissmatchName(string $docBlock, string $parameterName): void
    {
        $resolvedType = $this->paramTypeDocBlockResolver->resolve($docBlock, $parameterName);
        $this->assertNull($resolvedType);
    }

    /**
     * @return Iterator<string[]>
     */
    public static function provideDataMissmatchName(): Iterator
    {
        yield ['/** @param Type[] $name */', '___not'];
    }
}

<?php

declare(strict_types=1);

namespace Symplify\PackageBuilder\Tests\Strings;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symplify\PackageBuilder\Strings\StringFormatConverter;

final class StringFormatConverterTest extends TestCase
{
    private StringFormatConverter $stringFormatConverter;

    protected function setUp(): void
    {
        $this->stringFormatConverter = new StringFormatConverter();
    }

    #[DataProvider('provideCasesForCamelCaseToUnderscore')]
    public function testCamelCaseToUnderscore(string $input, string $expectedUnderscored): void
    {
        $underscoredString = $this->stringFormatConverter->camelCaseToUnderscore($input);
        $this->assertSame($expectedUnderscored, $underscoredString);
    }

    public static function provideCasesForCamelCaseToUnderscore(): Iterator
    {
        yield ['hiTom', 'hi_tom'];
        yield ['GPWebPay', 'gp_web_pay'];
        yield ['bMode', 'b_mode'];
    }

    #[DataProvider('provideCasesForUnderscoreAndHyphenToCamelCase')]
    public function testUnderscoreAndHyphenToCamelCase(string $input, string $expected): void
    {
        $camelCaseString = $this->stringFormatConverter->underscoreAndHyphenToCamelCase($input);
        $this->assertSame($expected, $camelCaseString);
    }

    public static function provideCasesForUnderscoreAndHyphenToCamelCase(): Iterator
    {
        yield ['hi_tom', 'hiTom'];
        yield ['hi-tom', 'hiTom'];
        yield ['hi-john_doe', 'hiJohnDoe'];
    }
}

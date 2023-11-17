<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Strings;

use MonorepoBuilderPrefix202311\Nette\Utils\Strings;
/**
 * @api
 * @see \Symplify\PackageBuilder\Tests\Strings\StringFormatConverterTest
 */
final class StringFormatConverter
{
    /**
     * @var string
     * @see https://regex101.com/r/rl1nvl/1
     */
    private const BIG_LETTER_REGEX = '#([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]*)#';
    public function underscoreAndHyphenToCamelCase(string $value) : string
    {
        $underscoreToHyphensValue = \str_replace(['_', '-'], ' ', $value);
        $uppercasedWords = \ucwords($underscoreToHyphensValue);
        $value = \str_replace(' ', '', $uppercasedWords);
        return \lcfirst($value);
    }
    public function camelCaseToUnderscore(string $input) : string
    {
        return $this->camelCaseToGlue($input, '_');
    }
    public function camelCaseToDashed(string $input) : string
    {
        return $this->camelCaseToGlue($input, '-');
    }
    /**
     * @param array<int|string, mixed> $items
     * @return array<int|string, mixed>
     */
    public function camelCaseToUnderscoreInArrayKeys(array $items) : array
    {
        foreach ($items as $key => $value) {
            if (!\is_string($key)) {
                continue;
            }
            $newKey = $this->camelCaseToUnderscore($key);
            if ($key === $newKey) {
                continue;
            }
            $items[$newKey] = $value;
            unset($items[$key]);
        }
        return $items;
    }
    private function camelCaseToGlue(string $input, string $glue) : string
    {
        $matches = Strings::matchAll($input, self::BIG_LETTER_REGEX);
        $parts = [];
        foreach ($matches as $match) {
            $parts[] = $match[0] === \strtoupper((string) $match[0]) ? \strtolower($match[0]) : \lcfirst((string) $match[0]);
        }
        return \implode($glue, $parts);
    }
}

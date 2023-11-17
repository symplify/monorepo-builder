<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\ComposerJsonManipulator\Json;

use MonorepoBuilderPrefix202311\Nette\Utils\Strings;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\Option;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Parameter\ParameterProvider;
final class JsonInliner
{
    /**
     * @var \Symplify\PackageBuilder\Parameter\ParameterProvider
     */
    private $parameterProvider;
    /**
     * @var string
     * @see https://regex101.com/r/jhWo9g/1
     */
    private const SPACE_REGEX = '#\\s+#';
    public function __construct(ParameterProvider $parameterProvider)
    {
        $this->parameterProvider = $parameterProvider;
    }
    public function inlineSections(string $jsonContent) : string
    {
        if (!$this->parameterProvider->hasParameter(Option::INLINE_SECTIONS)) {
            return $jsonContent;
        }
        $inlineSections = $this->parameterProvider->provideArrayParameter(Option::INLINE_SECTIONS);
        foreach ($inlineSections as $inlineSection) {
            $pattern = '#("' . \preg_quote($inlineSection, '#') . '": )\\[(.*?)\\](,)#ms';
            $jsonContent = Strings::replace($jsonContent, $pattern, static function (array $match) : string {
                $inlined = Strings::replace($match[2], self::SPACE_REGEX, ' ');
                $inlined = \trim($inlined);
                $inlined = '[' . $inlined . ']';
                return $match[1] . $inlined . $match[3];
            });
        }
        return $jsonContent;
    }
}

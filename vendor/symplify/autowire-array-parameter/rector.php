<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202408;

use MonorepoBuilderPrefix202408\Rector\Config\RectorConfig;
use MonorepoBuilderPrefix202408\Rector\PHPUnit\Set\PHPUnitSetList;
use MonorepoBuilderPrefix202408\Rector\Set\ValueObject\LevelSetList;
use MonorepoBuilderPrefix202408\Rector\Set\ValueObject\SetList;
return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        // @todo bump to PHP 8.1
        LevelSetList::UP_TO_PHP_80,
        SetList::CODING_STYLE,
        SetList::TYPE_DECLARATION,
        SetList::NAMING,
        SetList::PRIVATIZATION,
        SetList::EARLY_RETURN,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
    ]);
    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);
    $rectorConfig->importNames();
    $rectorConfig->skip(['*/Source/*', '*/Fixture/*']);
};

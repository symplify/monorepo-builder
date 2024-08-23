<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202408\Symplify\EasyTesting\PHPUnit;

/**
 * @api
 */
final class StaticPHPUnitEnvironment
{
    /**
     * Never ever used static methods if not neccesary, this is just handy for tests + src to prevent duplication.
     */
    public static function isPHPUnitRun() : bool
    {
        return \defined('MonorepoBuilderPrefix202408\\PHPUNIT_COMPOSER_INSTALL') || \defined('MonorepoBuilderPrefix202408\\__PHPUNIT_PHAR__');
    }
}

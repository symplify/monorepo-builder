<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202311\Symplify\EasyTesting\PHPUnit;

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
        return \defined('MonorepoBuilderPrefix202311\\PHPUNIT_COMPOSER_INSTALL') || \defined('MonorepoBuilderPrefix202311\\__PHPUNIT_PHAR__');
    }
}

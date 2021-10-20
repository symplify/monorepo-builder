<?php

declare (strict_types=1);
namespace MonorepoBuilder20211020\Symplify\PackageBuilder\Configuration;

/**
 * @api
 */
final class StaticEolConfiguration
{
    public static function getEolChar() : string
    {
        return "\n";
    }
}

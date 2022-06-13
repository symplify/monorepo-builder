<?php

declare (strict_types=1);
namespace MonorepoBuilder202206\Symplify\PackageBuilder\Configuration;

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

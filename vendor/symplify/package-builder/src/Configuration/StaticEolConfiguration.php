<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202308\Symplify\PackageBuilder\Configuration;

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

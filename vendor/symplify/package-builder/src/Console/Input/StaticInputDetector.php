<?php

declare (strict_types=1);
namespace MonorepoBuilder202301\Symplify\PackageBuilder\Console\Input;

use MonorepoBuilder202301\Symfony\Component\Console\Input\ArgvInput;
/**
 * @api
 */
final class StaticInputDetector
{
    public static function isDebug() : bool
    {
        $argvInput = new ArgvInput();
        return $argvInput->hasParameterOption(['--debug', '-v', '-vv', '-vvv']);
    }
}

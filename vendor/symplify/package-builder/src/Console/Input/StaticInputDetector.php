<?php

declare (strict_types=1);
namespace MonorepoBuilder20220612\Symplify\PackageBuilder\Console\Input;

use MonorepoBuilder20220612\Symfony\Component\Console\Input\ArgvInput;
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

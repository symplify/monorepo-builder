<?php

declare (strict_types=1);
namespace MonorepoBuilder20210721\Symplify\PackageBuilder\Console\Input;

use MonorepoBuilder20210721\Symfony\Component\Console\Input\ArgvInput;
final class StaticInputDetector
{
    public static function isDebug() : bool
    {
        $argvInput = new \MonorepoBuilder20210721\Symfony\Component\Console\Input\ArgvInput();
        return $argvInput->hasParameterOption(['--debug', '-v', '-vv', '-vvv']);
    }
}

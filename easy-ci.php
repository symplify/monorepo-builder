<?php

declare(strict_types=1);

use Symfony\Component\Console\Application;
use Symplify\EasyCI\Config\EasyCIConfig;
use Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;

return static function (EasyCIConfig $easyCIConfig): void {
    $easyCIConfig->typesToSkip([
        AbstractSymplifyKernel::class,
        Application::class,
    ]);
};

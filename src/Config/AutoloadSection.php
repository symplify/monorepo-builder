<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Config;

enum AutoloadSection: string
{
    case Autoload = 'autoload';
    case AutoloadDev = 'autoload-dev';
}

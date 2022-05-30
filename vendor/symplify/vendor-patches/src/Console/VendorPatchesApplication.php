<?php

declare (strict_types=1);
namespace MonorepoBuilder20220530\Symplify\VendorPatches\Console;

use MonorepoBuilder20220530\Symfony\Component\Console\Application;
use MonorepoBuilder20220530\Symfony\Component\Console\Command\Command;
final class VendorPatchesApplication extends \MonorepoBuilder20220530\Symfony\Component\Console\Application
{
    /**
     * @param Command[] $commands
     */
    public function __construct(array $commands)
    {
        $this->addCommands($commands);
        parent::__construct();
    }
}

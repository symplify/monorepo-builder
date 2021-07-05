<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210705\Symfony\Component\VarDumper\Command\Descriptor;

use MonorepoBuilder20210705\Symfony\Component\Console\Output\OutputInterface;
use MonorepoBuilder20210705\Symfony\Component\VarDumper\Cloner\Data;
/**
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 */
interface DumpDescriptorInterface
{
    public function describe(\MonorepoBuilder20210705\Symfony\Component\Console\Output\OutputInterface $output, \MonorepoBuilder20210705\Symfony\Component\VarDumper\Cloner\Data $data, array $context, int $clientId) : void;
}

<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210727\Symfony\Component\Console\Tester\Constraint;

use MonorepoBuilder20210727\PHPUnit\Framework\Constraint\Constraint;
use MonorepoBuilder20210727\Symfony\Component\Console\Command\Command;
final class CommandIsSuccessful extends \MonorepoBuilder20210727\PHPUnit\Framework\Constraint\Constraint
{
    /**
     * {@inheritdoc}
     */
    public function toString() : string
    {
        return 'is successful';
    }
    /**
     * {@inheritdoc}
     */
    protected function matches($other) : bool
    {
        return \MonorepoBuilder20210727\Symfony\Component\Console\Command\Command::SUCCESS === $other;
    }
    /**
     * {@inheritdoc}
     */
    protected function failureDescription($other) : string
    {
        return 'the command ' . $this->toString();
    }
}

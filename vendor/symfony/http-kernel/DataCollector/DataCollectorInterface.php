<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210706\Symfony\Component\HttpKernel\DataCollector;

use MonorepoBuilder20210706\Symfony\Component\HttpFoundation\Request;
use MonorepoBuilder20210706\Symfony\Component\HttpFoundation\Response;
use MonorepoBuilder20210706\Symfony\Contracts\Service\ResetInterface;
/**
 * DataCollectorInterface.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface DataCollectorInterface extends \MonorepoBuilder20210706\Symfony\Contracts\Service\ResetInterface
{
    /**
     * Collects data for the given Request and Response.
     */
    public function collect(\MonorepoBuilder20210706\Symfony\Component\HttpFoundation\Request $request, \MonorepoBuilder20210706\Symfony\Component\HttpFoundation\Response $response, \Throwable $exception = null);
    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName();
}

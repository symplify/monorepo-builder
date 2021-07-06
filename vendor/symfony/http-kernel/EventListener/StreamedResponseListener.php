<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210706\Symfony\Component\HttpKernel\EventListener;

use MonorepoBuilder20210706\Symfony\Component\EventDispatcher\EventSubscriberInterface;
use MonorepoBuilder20210706\Symfony\Component\HttpFoundation\StreamedResponse;
use MonorepoBuilder20210706\Symfony\Component\HttpKernel\Event\ResponseEvent;
use MonorepoBuilder20210706\Symfony\Component\HttpKernel\KernelEvents;
/**
 * StreamedResponseListener is responsible for sending the Response
 * to the client.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final
 */
class StreamedResponseListener implements \MonorepoBuilder20210706\Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    /**
     * Filters the Response.
     */
    public function onKernelResponse(\MonorepoBuilder20210706\Symfony\Component\HttpKernel\Event\ResponseEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $response = $event->getResponse();
        if ($response instanceof \MonorepoBuilder20210706\Symfony\Component\HttpFoundation\StreamedResponse) {
            $response->send();
        }
    }
    public static function getSubscribedEvents() : array
    {
        return [\MonorepoBuilder20210706\Symfony\Component\HttpKernel\KernelEvents::RESPONSE => ['onKernelResponse', -1024]];
    }
}

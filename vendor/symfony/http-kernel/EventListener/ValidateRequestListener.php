<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210705\Symfony\Component\HttpKernel\EventListener;

use MonorepoBuilder20210705\Symfony\Component\EventDispatcher\EventSubscriberInterface;
use MonorepoBuilder20210705\Symfony\Component\HttpKernel\Event\RequestEvent;
use MonorepoBuilder20210705\Symfony\Component\HttpKernel\KernelEvents;
/**
 * Validates Requests.
 *
 * @author Magnus Nordlander <magnus@fervo.se>
 *
 * @final
 */
class ValidateRequestListener implements \MonorepoBuilder20210705\Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    /**
     * Performs the validation.
     */
    public function onKernelRequest(\MonorepoBuilder20210705\Symfony\Component\HttpKernel\Event\RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $request = $event->getRequest();
        if ($request::getTrustedProxies()) {
            $request->getClientIps();
        }
        $request->getHost();
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() : array
    {
        return [\MonorepoBuilder20210705\Symfony\Component\HttpKernel\KernelEvents::REQUEST => [['onKernelRequest', 256]]];
    }
}

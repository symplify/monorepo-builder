<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210913\Symfony\Component\HttpKernel\EventListener;

use MonorepoBuilder20210913\Symfony\Component\EventDispatcher\EventSubscriberInterface;
use MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Request;
use MonorepoBuilder20210913\Symfony\Component\HttpFoundation\RequestStack;
use MonorepoBuilder20210913\Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use MonorepoBuilder20210913\Symfony\Component\HttpKernel\Event\KernelEvent;
use MonorepoBuilder20210913\Symfony\Component\HttpKernel\Event\RequestEvent;
use MonorepoBuilder20210913\Symfony\Component\HttpKernel\KernelEvents;
use MonorepoBuilder20210913\Symfony\Component\Routing\RequestContextAwareInterface;
/**
 * Initializes the locale based on the current request.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final
 */
class LocaleListener implements \MonorepoBuilder20210913\Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    private $router;
    private $defaultLocale;
    private $requestStack;
    public function __construct(\MonorepoBuilder20210913\Symfony\Component\HttpFoundation\RequestStack $requestStack, string $defaultLocale = 'en', \MonorepoBuilder20210913\Symfony\Component\Routing\RequestContextAwareInterface $router = null)
    {
        $this->defaultLocale = $defaultLocale;
        $this->requestStack = $requestStack;
        $this->router = $router;
    }
    /**
     * @param \Symfony\Component\HttpKernel\Event\KernelEvent $event
     */
    public function setDefaultLocale($event)
    {
        $event->getRequest()->setDefaultLocale($this->defaultLocale);
    }
    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     */
    public function onKernelRequest($event)
    {
        $request = $event->getRequest();
        $this->setLocale($request);
        $this->setRouterContext($request);
    }
    /**
     * @param \Symfony\Component\HttpKernel\Event\FinishRequestEvent $event
     */
    public function onKernelFinishRequest($event)
    {
        if (null !== ($parentRequest = $this->requestStack->getParentRequest())) {
            $this->setRouterContext($parentRequest);
        }
    }
    private function setLocale(\MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Request $request)
    {
        if ($locale = $request->attributes->get('_locale')) {
            $request->setLocale($locale);
        }
    }
    private function setRouterContext(\MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Request $request)
    {
        if (null !== $this->router) {
            $this->router->getContext()->setParameter('_locale', $request->getLocale());
        }
    }
    public static function getSubscribedEvents() : array
    {
        return [\MonorepoBuilder20210913\Symfony\Component\HttpKernel\KernelEvents::REQUEST => [
            ['setDefaultLocale', 100],
            // must be registered after the Router to have access to the _locale
            ['onKernelRequest', 16],
        ], \MonorepoBuilder20210913\Symfony\Component\HttpKernel\KernelEvents::FINISH_REQUEST => [['onKernelFinishRequest', 0]]];
    }
}

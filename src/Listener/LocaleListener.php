<?php

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleListener
{
    private $requestStack;
    private $defaultLocale;

    public function __construct(RequestStack $requestStack, string $defaultLocale)
    {
        $this->requestStack = $requestStack;
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request) {
            $session = $request->getSession();
            $locale = $session->get('_locale', $this->defaultLocale);
            $request->setLocale($locale);
        }
    }
}
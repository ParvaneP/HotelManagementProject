<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LanguageController extends AbstractController
{
    
    #[Route('/switch-language/{locale}', name: 'app_switch_language')]
    public function switchLanguage($locale, SessionInterface $session): RedirectResponse
    {
        $availableLocales = ['en', 'fa'];
        if (!in_array($locale, $availableLocales)) {
            $locale = 'en';
        }
        $session->set('_locale', $locale);
        return $this->redirect($this->generateUrl('app_landing_page'));
    }
}
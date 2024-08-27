<?php

namespace App\Security\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;
    private $authorizationChecker;

    public function __construct(RouterInterface $router, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        // Get the roles of the user
        $roles = $token->getUser()->getRoles();

        // Determine the redirection route based on the role
        if (in_array('ROLE_ADMIN', $roles, true)) {
            $redirectUrl = $this->router->generate('app_guest_index');
        } elseif (in_array('ROLE_GUEST', $roles, true)) {
            $redirectUrl = $this->router->generate('app_guest_reservation_index');
        } else {
            // Default redirection if no role matches
            $redirectUrl = $this->router->generate('app_landing_page');
        }

        return new RedirectResponse($redirectUrl);
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: Ines
 * Date: 30/01/2018
 * Time: 22:15
 */

namespace AppBundle\Redirection;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AfterLoginRedirection implements AuthenticationSuccessHandlerInterface
{


    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // Get list of roles for current user
        $roles = $token->getRoles();
        // Tranform this list in array
        $rolesTab = array_map(function ($role) {
            return $role->getRole();
        }, $roles);
        // If is a admin or super admin we redirect to the backoffice area
        if (in_array('ROLE_VENDEUR', $rolesTab, true))
            $redirection = new RedirectResponse($this->router->generate('homepage'));
        // otherwise, if is a commercial user we redirect to the crm area
        elseif (in_array('ROLE_MEDECIN', $rolesTab, true))
            $redirection = new RedirectResponse($this->router->generate('homepage'));
        elseif (in_array('ROLE_JD', $rolesTab, true))
            $redirection = new RedirectResponse($this->router->generate('homepage'));
        elseif (in_array('ROLE_ENSEIGNANT', $rolesTab, true))
            $redirection = new RedirectResponse($this->router->generate('homepage'));
        // otherwise we redirect user to the member area
        elseif (in_array('ROLE_BS', $rolesTab, true))
            $redirection = new RedirectResponse($this->router->generate('homepage'));
        elseif (in_array('ROLE_ADMIN', $rolesTab, true))
            $redirection = new RedirectResponse($this->router->generate('fournisseur'));
        else
            $redirection = new RedirectResponse($this->router->generate('homepage'));
        return $redirection;
    }
}

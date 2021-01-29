<?php
/**
 * @author Eric Richer <eric.richer@vistoconsulting.com>
 * @copyright Eric Richer
 */

namespace LmcUserApiToolsAuth;


use Laminas\ApiTools\MvcAuth\Authentication\AdapterInterface;
use Laminas\ApiTools\MvcAuth\Identity\AuthenticatedIdentity;
use Laminas\ApiTools\MvcAuth\Identity\GuestIdentity;
use Laminas\ApiTools\MvcAuth\MvcAuthEvent;
use Laminas\Authentication\AuthenticationService;
use Laminas\Http\Request;
use Laminas\Http\Response;
use LmcUser\Entity\UserInterface;

class AuthAdapter implements AdapterInterface
{
    /** @var AuthenticationService */
    private $authService;

    /**
     * AuthAdapter constructor.
     * @param AuthenticationService $authService
     */
    public function __construct( AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /** @inheritdoc  */
    public function provides()
    {
        return ['LmcUserAuth'];
    }

    /** @inheritdoc  */
    public function matches($type)
    {
        return $type === 'LmcUserAuth';
    }

    public function getTypeFromRequest(Request $request)
    {
        // TODO: Implement getTypeFromRequest() method.
    }

    public function preAuth(Request $request, Response $response)
    {
        // TODO: Implement preAuth() method.
    }

    public function authenticate(Request $request, Response $response, MvcAuthEvent $mvcAuthEvent)
    {
        // Check is the user is authenticated
        if ($this->authService->hasIdentity()) {
            // Get the identity
            /** @var UserInterface $identity */
            $identity = $this->authService->getIdentity();
            $authIdentity = new AuthenticatedIdentity($identity);
            $authIdentity->setName($identity->getId());
            return $authIdentity;
        } else {
            return new GuestIdentity();
        }
    }
}

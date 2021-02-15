<?php

namespace LmcUserApiToolsAuthTest\Adapter;

use Laminas\ApiTools\MvcAuth\Identity\AuthenticatedIdentity;
use Laminas\ApiTools\MvcAuth\Identity\GuestIdentity;
use Laminas\ApiTools\MvcAuth\MvcAuthEvent;
use Laminas\Http\Request;
use Laminas\Authentication\AuthenticationService;
use Laminas\Http\Response;
use LmcUser\Entity\User;
use LmcUserApiToolsAuth\Adapter\AuthAdapter;
use PHPUnit\Framework\TestCase;

class AdapterTest extends TestCase
{
    public function testAdapter()
    {
        $authService = $this->createMock(AuthenticationService::class);
        $adapter = new AuthAdapter($authService);

        $this->assertEquals(true, $adapter->matches('LmcUserAuth'));
        $this->assertEquals(['LmcUserAuth'], $adapter->provides());
    }

    public function testAuthenticateGuest()
    {
        $authService = $this->createMock(AuthenticationService::class);
        $authService->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(false));
        $adapter = new AuthAdapter($authService);
        $request = new Request();
        $response = new Response();
        $event = $this->createMock(MvcAuthEvent::class);
        $this->assertInstanceOf(GuestIdentity::class, $adapter->authenticate($request, $response, $event));
    }

    public function testAuthenticateUser()
    {
        $identity = new User();
        $identity->setId('1');
        $authService = $this->createMock(AuthenticationService::class);
        $authService->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(true));
        $authService->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($identity));
        $adapter = new AuthAdapter($authService);
        $request = new Request();
        $response = new Response();
        $event = $this->createMock(MvcAuthEvent::class);
        $authenticatedIdentity = $adapter->authenticate($request, $response, $event);
        $this->assertInstanceOf(AuthenticatedIdentity::class, $authenticatedIdentity);
        $this->assertInstanceOf(User::class, $authenticatedIdentity->getAuthenticationIdentity());
    }

    public function testGetTypeFromRequest()
    {
        $authService = $this->createMock(AuthenticationService::class);
        $adapter = new AuthAdapter($authService);
        $request = new Request();
        $this->assertFalse($adapter->getTypeFromRequest($request));
    }

    public function testPreFlight()
    {
        $adapter = new AuthAdapter($this->createMock(AuthenticationService::class));
        $this->assertFalse($adapter->preAuth(new Request(), new Response()));
    }
}

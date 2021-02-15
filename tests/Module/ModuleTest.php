<?php


namespace LmcUserApiToolsAuthTest\Module;


use Laminas\ApiTools\MvcAuth\Authentication\DefaultAuthenticationListener;
use LmcUserApiToolsAuth\Adapter\AuthAdapterDelegatorFactory;
use LmcUserApiToolsAuth\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    public function testModule()
    {
        $module = new Module();
        $serviceConfig = $module->getServiceConfig();
        $this->assertIsArray($serviceConfig);
        $this->assertArrayHasKey('delegators', $serviceConfig);
        $this->assertArrayHasKey(DefaultAuthenticationListener::class, $serviceConfig['delegators']);
        $this->assertIsArray($serviceConfig['delegators'][DefaultAuthenticationListener::class]);
        $this->assertEquals(AuthAdapterDelegatorFactory::class, $serviceConfig['delegators'][DefaultAuthenticationListener::class][0]);
    }
}

<?php
namespace LmcUserApiToolsAuthTest\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\MvcAuth\Authentication\AdapterInterface;
use Laminas\ApiTools\MvcAuth\Authentication\DefaultAuthenticationListener;
use Laminas\ApiTools\MvcAuth\Factory\DefaultAuthenticationListenerFactory;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceManager;
use LmcUserApiToolsAuth\Adapter\AuthAdapter;
use LmcUserApiToolsAuth\Adapter\AuthAdapterDelegatorFactory;
use PHPUnit\Framework\TestCase;

class DelegatorFactoryTest extends TestCase
{
    public function testNoAuthConfig():void
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', []);
        $delegatorFactory = new AuthAdapterDelegatorFactory();
        $listenerFactory = new ListenerFactory();
        $listener = $delegatorFactory($serviceManager, 'test', $listenerFactory, []);
        $this->assertInstanceOf(testListener::class, $listener);
    }

    public function testNoAuthAdapter():void
    {
        $serviceManager = new ServiceManager();
        $delegatorFactory = new AuthAdapterDelegatorFactory();
        $listenerFactory = new ListenerFactory();
        $serviceManager->setService('config', [
            'api-tools-mvc-auth' => [
                'authentication' => [
                    'adapters' => [],
                ],
            ],
        ]);
        $listener = $delegatorFactory($serviceManager, 'test', $listenerFactory, []);
        $this->assertInstanceOf(testListener::class, $listener);
        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', [
            'api-tools-mvc-auth' => [
                'authentication' => [
                    'adapters' => [
                        'testAuth' => 1234,
                    ],
                ],
            ],
        ]);
        $listener = $delegatorFactory($serviceManager, 'test', $listenerFactory, []);
        $this->assertInstanceOf(testListener::class, $listener);
    }

    public function testAuthAdapterMissingOptions():void
    {
        $delegatorFactory = new AuthAdapterDelegatorFactory();
        $listenerFactory = new ListenerFactory();
        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', [
            'api-tools-mvc-auth' => [
                'authentication' => [
                    'adapters' => [
                        'testAuth' => [
                            'adapter' => AuthAdapter::class,
                        ],
                    ],
                ],
            ],
        ]);
        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage('Missing adapter options for LmcUserApiToolsAuth');
        $listener = $delegatorFactory($serviceManager, 'test', $listenerFactory, []);

        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', [
            'api-tools-mvc-auth' => [
                'authentication' => [
                    'adapters' => [
                        'testAuth' => [
                            'adapter' => AuthAdapter::class,
                            'options' => 'not an array',
                        ],
                    ],
                ],
            ],
        ]);
        $listener = $delegatorFactory($serviceManager, 'test', $listenerFactory, []);
    }
    public function testNoAuthServiceOptions() :void
    {
        $delegatorFactory = new AuthAdapterDelegatorFactory();
        $listenerFactory = new ListenerFactory();
        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage('Invalid LmcUserApiToolsAuth adapter options');
        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', [
            'api-tools-mvc-auth' => [
                'authentication' => [
                    'adapters' => [
                        'testAuth' => [
                            'adapter' => AuthAdapter::class,
                            'options' => [
                            ]
                        ],
                    ],
                ],
            ],
        ]);
        $listener = $delegatorFactory($serviceManager, 'test', $listenerFactory, []);

        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', [
            'api-tools-mvc-auth' => [
                'authentication' => [
                    'adapters' => [
                        'testAuth' => [
                            'adapter' => AuthAdapter::class,
                            'options' => [
                                'authentication_service' => 'dummy'
                            ]
                        ],
                    ],
                ],
            ],
        ]);
        $listener = $delegatorFactory($serviceManager, 'test', $listenerFactory, []);
    }

    public function testAuthAdapterSuccess(): void
    {
        $delegatorFactory = new AuthAdapterDelegatorFactory();
        $listenerFactory = new ListenerFactory();
        $serviceManager = new ServiceManager();
        $serviceManager->setService('authservice', $this->createMock(AuthenticationService::class));
        $serviceManager->setService('config', [
            'api-tools-mvc-auth' => [
                'authentication' => [
                    'adapters' => [
                        'testAuth' => [
                            'adapter' => AuthAdapter::class,
                            'options' => [
                                'authentication_service' => 'authservice'
                            ]
                        ],
                    ],
                ],
            ],
        ]);
        $listener = $delegatorFactory($serviceManager, 'test', $listenerFactory, []);
        $this->assertInstanceOf(AdapterInterface::class, $listener->getAdapter());
    }
}

class ListenerFactory
{
    function __invoke()
    {
        return new testListener();
    }
}

class testListener
{
    public $adapter;
    public function attach(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
    public function getAdapter()
    {
        return $this->adapter;
    }
}

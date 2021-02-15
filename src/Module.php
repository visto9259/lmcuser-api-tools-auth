<?php


namespace LmcUserApiToolsAuth;

use LmcUserApiToolsAuth\Adapter\AuthAdapterDelegatorFactory;
use Laminas\ApiTools\MvcAuth\Authentication\DefaultAuthenticationListener;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ServiceProviderInterface
{
    /** @inheritdoc  */
    public function getServiceConfig()
    {
        return [
            'delegators' => [
                DefaultAuthenticationListener::class => [
                    AuthAdapterDelegatorFactory::class,
                ],
            ],
        ];
    }
}

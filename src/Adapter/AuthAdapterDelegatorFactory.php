<?php
/**
 * @author Eric Richer <eric.richer@vistoconsulting.com>
 * @copyright Eric Richer
 */


namespace LmcUserApiToolsAuth;


use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;

class AuthAdapterDelegatorFactory implements DelegatorFactoryInterface
{

    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $listener = $callback();
        $config = $container->get('config');

        // Make sure that there are authentications adapters defined
        if (!isset($config['api-tools-mvc-auth']['authentication']['adapters']) || !is_array($config['api-tools-mvc-auth']['authentication']['adapters'])) {
            return $listener;
        }
        foreach ($config['api-tools-mvc-auth']['authentication']['adapters'] as $type => $adapterConfig) {
            if (!isset($adapterConfig['adapter']) || !is_string($adapterConfig['adapter'])) {
                continue;
            } elseif ($adapterConfig['adapter'] == AuthAdapter::class) {
                if (!isset($adapterConfig['options']) ||!is_array($adapterConfig['options'])) {
                    throw new ServiceNotCreatedException('Missing adapter options for LmcUserApiToolsAuth');
                }
                //
                $adapterOptions = $adapterConfig['options'];
                if (isset($adapterOptions['authentication_service']) && $container->has($adapterOptions['authentication_service'])) {
                    $adapter = new AuthAdapter($container->get($adapterOptions['authentication_service']));
                    $listener->attach($adapter);
                } else {
                    throw new ServiceNotCreatedException('Invalid LmcUserApiToolsAuth adapter options');
                }
            }
        }
        return $listener;
    }
}

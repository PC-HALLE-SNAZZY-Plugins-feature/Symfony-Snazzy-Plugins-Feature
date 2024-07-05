<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\DependencyInjection\ServiceLocator;

class PluginServiceLocator
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getService(string $serviceName): ?object
    {

        // ! this function should return the service object by its name 
         
        dd($this->container);
        $serviceName = TestService::class;
        if ($this->container->has($serviceName)) {
            return $this->container->get($serviceName);
        }
        return null;
    }
}

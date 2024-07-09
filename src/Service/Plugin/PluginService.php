<?php

namespace App\Service\Plugin;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Credentials;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PluginService
{
    private $entityManager;
    private $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function saveCredentials($user, object $plugin, array $credentials_data)
    {
        $credentials = new Credentials();
        
        // ! you should send user as object not id from the controller
        $user = $this->entityManager->getRepository(User::class)->find($user);
        // ! -------------------------------------------------------------------

        try {
            $credentials->setUser($user);
            $credentials->setPlugin($plugin);

            $fields_array = $plugin->getCredentialsFormFields() ?? [];
            $credentials_array = [];

            foreach ($fields_array as $field) {
                $credentials_array[$field] = $credentials_data[$field];
            }

            $credentials_object = (object) $credentials_array;

            $credentials->setCredentials($credentials_object);

            // ! you should call the verifyCredentials function from the plugin service
            // if (!$this->verifyCredentials($plugin, $credentials_object)) {
            //     return false;
            // }

            $this->entityManager->persist($credentials);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function verifyCredentials($plugin, $credentials_object)
    {

        $serviceName = ucfirst(strtolower(str_replace(' ', '', $plugin->getName()))) . 'Service';
        $serviceName = str_replace(['"', "'"], '', $serviceName);
        dd($serviceName);
        $ServiceInstance = $this->container->get($serviceName::class);
        dd($ServiceInstance);
    
        if (!method_exists($ServiceInstance, 'verifyCredentials')) {
            return false;
        }
    
        try {
            return $ServiceInstance->verifyCredentials($credentials_object);
        } catch (\Exception $e) {
            return false;
        }
    }
    
}

<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Credentials;
use App\Service\PluginServiceLocator;

class PluginService
{
    private $entityManager;
    private $serviceLocator;

    public function __construct(EntityManagerInterface $entityManager, PluginServiceLocator $serviceLocator)
    {
        $this->entityManager = $entityManager;
        $this->serviceLocator = $serviceLocator;
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

            if (!$this->verifyCredentials($plugin, $credentials_object)) {
                return false;
            }

            $this->entityManager->persist($credentials);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function verifyCredentials($plugin, $credentials_object)
    {

        $serviceName = ucfirst(strtolower($plugin->getName())) . 'Service';
        
        $service = $this->serviceLocator->getService($serviceName);
        dd($service);
    
        $methodName = 'verifyCredentials';
    
        if (!method_exists($service, $methodName)) {
            return false;
        }
    
        try {
            return $service->$methodName($credentials_object);
        } catch (\Exception $e) {
            return false;
        }
    }
    
}

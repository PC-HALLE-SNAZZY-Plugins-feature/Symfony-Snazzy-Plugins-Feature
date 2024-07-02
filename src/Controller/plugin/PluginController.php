<?php

namespace App\Controller\plugin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PluginController extends AbstractController
{
    #[Route('/plugin', name: 'app_plugin')]
    public function index(): Response
    {
        return $this->render('plugin/index.html.twig', [
            'controller_name' => 'PluginController',
        ]);
    }
}

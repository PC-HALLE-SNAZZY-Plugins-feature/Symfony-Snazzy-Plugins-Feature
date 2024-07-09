<?php

namespace App\Controller\plugin;

use App\Entity\Plugin;
use App\Form\PluginType;
use App\Repository\PluginRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\RouterInterface;
use App\Service\Plugin\PluginService;
use App\Service\TestService;

class PluginController extends AbstractController
{
    private $entityManager;
    private $pluginRepository;
    private $router;
    private $pluginService;

    public function __construct(
        EntityManagerInterface $entityManager,
        PluginRepository $pluginRepository,
        RouterInterface $router,
        PluginService $pluginService
    ) {
        $this->entityManager = $entityManager;
        $this->pluginRepository = $pluginRepository;
        $this->router = $router;
        $this->pluginService = $pluginService;
    }

    /**
     * ? in this Function we can add, see all the plugins
     * ? @Route("/admin/plugin", name="app_plugin").
     */

    #[Route('/admin/plugin', name: 'app_plugin')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $plugin = new Plugin();
        $form = $this->createForm(PluginType::class, $plugin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $routes = $this->router->getRouteCollection();
                $routeNames = [];

                foreach ($routes as $routeName => $route) {
                    $routeNames[] = $routeName;
                }

                $routeNames = array_filter($routeNames, function ($routeName) {
                    return !(strpos($routeName, '_edit') !== false || strpos($routeName, '_delete') !== false || strpos($routeName, '_show') !== false);
                });

                if (!in_array($plugin->getDashboardPath(), $routeNames)) {
                    $this->addFlash('error', 'Route Name is not valid');

                    return $this->redirectToRoute('app_plugin');
                }

                $this->entityManager->persist($plugin);
                $this->entityManager->flush();
                $this->addFlash('success', 'Plugin Created Successfully');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Plugin Creation Failed');
            }
        }

        $num_of_elements = 5;

        if ($request->request->get('_keyword')) {
            $plugins = $paginator->paginate(
                $this->pluginRepository->findBySearchTerm($request->request->get('_keyword')),
                $request->query->getInt('page', 1),
                $num_of_elements
            );
        } else {
            $plugins = $paginator->paginate(
                $this->pluginRepository->findAllDesc(),
                $request->query->getInt('page', 1),
                $num_of_elements
            );
        }

        return $this->render('plugins/plugin_dashboard/index.html.twig', [
            'form'            => $form->createView(),
            'plugins'         => $plugins,
            'num_of_elements' => count($plugins),

        ]);
    }


    /**
     * ? in this Function we can see the plugin
     * ? @Route("/admin/plugin/show/{id}", name="app_plugin_show").
     */

    #[Route('/admin/plugin/show/{id}', name: 'app_plugin_show')]
    public function show(Plugin $plugin, Request $request): Response
    {
        $fields = $plugin->getCredentialsFormFields();
        $credentials = $request->request->all();

        if ($request->isMethod('POST')) {
            foreach ($fields as $field) {
                if (empty($credentials[$field])) {
                    $this->addFlash('error', 'Please fill in all the required fields');

                    return $this->redirectToRoute('app_plugin_show', ['id' => $plugin->getId()]);
                }
            }


            try {
                // $user = $this->getUser();
                $user = 1;
                $this->pluginService->saveCredentials($user, $plugin, $credentials);
                $this->addFlash('success', 'Credentials Saved Successfully');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Credentials Saving Failed');
            }

            return $this->redirectToRoute('app_plugin_show', ['id' => $plugin->getId()]);
        }

        // Render the show template with the plugin and form fields
        return $this->render('plugins/plugin_dashboard/show.html.twig', [
            'plugin' => $plugin,
            'fields' => $fields,
        ]);
    }


    /**
     * ? in this Function we can edit the plugin
     * ? @Route("/admin/plugin/edit/{id}", name="app_plugin_edit").
     */

    #[Route('/admin/plugin/edit/{id}', name: 'app_plugin_edit')]
    public function edit(Plugin $plugin, Request $request): Response
    {
        $form = $this->createForm(PluginType::class, $plugin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                foreach ($plugin->getScreenShots() as $screenShot) {
                    if ($screenShot->getImageName() === null) {
                        $this->entityManager->remove($screenShot);
                    }
                }
                $this->entityManager->flush();
                $this->addFlash('success', 'Plugin Updated Successfully');

                return $this->redirectToRoute('app_plugin');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Plugin Update Failed');

                return $this->redirectToRoute('app_plugin');
            }
        }

        return $this->render('plugins/plugin_dashboard/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * ? in this Function we can delete the plugin
     * ? @Route("/admin/plugin/delete/{id}", name="app_plugin_delete").
     */

    #[Route('/admin/plugin/delete/{id}', name: 'app_plugin_delete')]
    public function delete(Plugin $plugin): Response
    {
        try {
            $this->entityManager->remove($plugin);
            $this->entityManager->flush();
            $this->addFlash('success', 'Plugin Deleted Successfully');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Plugin Deletion Failed');
        }

        return $this->redirectToRoute('app_plugin');
    }
}

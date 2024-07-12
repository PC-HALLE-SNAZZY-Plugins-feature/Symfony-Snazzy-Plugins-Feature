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
use App\Repository\RatingRepository;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class PluginController extends AbstractController
{
    private $entityManager;
    private $pluginRepository;
    private $router;
    private $pluginService;
    private $ratingRepository;
    private $categoryRepository;
    private $userRepository;



    public function __construct(
        EntityManagerInterface $entityManager,
        PluginRepository $pluginRepository,
        RouterInterface $router,
        PluginService $pluginService,
        RatingRepository $ratingRepository,
        CategoryRepository $categoryRepository,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->pluginRepository = $pluginRepository;
        $this->router = $router;
        $this->pluginService = $pluginService;
        $this->ratingRepository = $ratingRepository;
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
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

        return $this->render('plugins/admin/plugin/index.html.twig', [
            'form'            => $form->createView(),
            'plugins'         => $plugins,
            'num_of_elements' => count($plugins),

        ]);
    }


    /**
     * ? in this Function we can see the plugins of the user
     * ? @Route("/admin/plugin/my_plugins", name="app_my_plugins").
     */

    #[Route('plugin/my_plugins', name: 'app_my_plugins')]
    public function myPlugins()
    {
        return $this->render('plugins/user/plugin/my_plugins.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    /**
     * ? in this Function we can list the plugins
     * ? @Route("plugin/list", name="app_plugin_list").
     */

    #[Route('plugin/my_plugin/list', name: 'app_plugin_list')]
    public function listPlugins(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('searchTerm') ?? null;
        $category = $request->query->get('category');


        $plugins = $this->pluginRepository->findMyPluginsBySearchTerm('1', $searchTerm, $category);

        // Prepare data to send back
        $data = [];
        foreach ($plugins as $plugin) {
            $data[] = [
                'id'             => $plugin->getId(),
                'name'           => $plugin->getName(),
                'description'    => $plugin->getDescription(),
                'dashboard_path' => $this->generateUrl($plugin->getDashboardPath()),
                'uninstall_path' => $this->generateUrl('app_plugin_uninstall', ['id' => $plugin->getId()]),
                'image_name'     => $plugin->getImageName(),
                // 'plugin'        => $this->pluginRepository->find($plugin->getId()),
            ];
        }

        return $this->json($data);
    }


    /**
     * ? in this Function we can install the plugin
     * ? @Route("/admin/plugin/install/{id}", name="app_plugin_install").
     */

    #[Route('plugin/install/{id}', name: 'app_plugin_install')]
    public function installPlugin(Plugin $plugin, Request $request): Response
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
                $user = 1;

                /**
                 * ! you should send user as object not id from the controller
                 * ! i send it as id because i don't have the  authenticated user
                 * ? $user = $this->getUser();
                 */

                $this->pluginService->saveCredentials($user, $plugin, $credentials);
                $this->addFlash('success', 'Plugin Installed Successfully');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Plugin Installation Failed' . $e->getMessage());
            }
        }


        return $this->redirectToRoute('app_my_plugins');
    }


    /**
     * ? in this Function we can install the plugin
     * ? @Route("/admin/plugin/install/{id}", name="app_plugin_install").
     */

    #[Route('plugin/uninstall/{id}', name: 'app_plugin_uninstall')]
    public function uninstallPlugin(Plugin $plugin): Response
    {
        $user = 1;

        /**
         * ! you should send user as object not id from the controller
         * ! i send it as id because i don't have the  authenticated user
         * ? $user = $this->getUser();
         */

        try {
            $this->pluginService->uninstallPlugin($user, $plugin);
            $this->addFlash('success', 'Plugin Uninstalled Successfully');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Plugin Uninstallation Failed');
        }

        return $this->redirectToRoute('app_my_plugins');
    }

    /**
     * ? in this Function we can see the plugin
     * ? @Route("/admin/plugin/show/{id}", name="app_plugin_show").
     */

    #[Route('plugin/show/{id}', name: 'app_plugin_show')]
    public function show(Plugin $plugin, Request $request): Response
    {
        $fields = $plugin->getCredentialsFormFields();

        $ratings = $this->ratingRepository->findRatingsWithUser();

        // ? Filter out ratings with empty comments
        $ratings = array_filter($ratings, function ($rating) {
            return !empty($rating['comment']);
        });


        return $this->render('plugins/user/plugin/show.html.twig', [
            'plugin'      => $plugin,
            'fields'      => $fields,
            'RatingStats' => $this->pluginService->gatRatingStats($plugin),
            'last_rating' => array_slice($ratings, 0, 3),
            'rest_rating' => array_slice($ratings, 3),
        ]);
    }

    /**
     * ? in this Function we can rate the plugin
     * ? @Route("/admin/plugin/rate/{id}", name="app_plugin_rate").
     */

    #[Route('plugin/rate/{id}', name: 'app_plugin_rate')]
    public function rate(Plugin $plugin, Request $request): Response
    {
        $rating = $request->request->get('rating');
        $review = $request->request->get('review');



        if ($rating) {
            try {
                // $user = $this->getUser();
                $user = 1;
                $this->pluginService->ratePlugin($user, $plugin, $rating, $review);
                $this->addFlash('success', 'Plugin Rated Successfully');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Plugin Rating Failed');
            }
        }

        return $this->redirectToRoute('app_plugin_show', ['id' => $plugin->getId()]);
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
                    if (empty($screenShot->getImageName())) {
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

        return $this->render('plugins/admin/plugin/edit.html.twig', [
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

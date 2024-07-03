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

class PluginController extends AbstractController
{
    private $entityManager;
    private $pluginRepository;


    public function __construct(EntityManagerInterface $entityManager, PluginRepository $pluginRepository)
    {
        $this->entityManager = $entityManager;
        $this->pluginRepository = $pluginRepository;
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

        return $this->render('plugin/index.html.twig', [
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
    public function show(Plugin $plugin): Response
    {
        return $this->render('plugin/show.html.twig', [
            'plugin' => $plugin,
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
                $this->entityManager->flush();
                $this->addFlash('success', 'Plugin Updated Successfully');
                return $this->redirectToRoute('app_plugin');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Plugin Update Failed');
                return $this->redirectToRoute('app_plugin');
            }
        }

        return $this->render('plugin/edit.html.twig', [
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

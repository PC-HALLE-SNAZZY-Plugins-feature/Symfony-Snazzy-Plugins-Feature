<?php

namespace App\Controller\plugin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

class CategoryController extends AbstractController
{
    private $entityManager;
    private $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * ? in this Function we can add, see all the categories
     * ? @Route("/admin/plugin/category", name="app_plugin_category").
     */

    #[Route('/admin/plugin/category', name: 'app_plugin_category')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->entityManager->persist($category);
                $this->entityManager->flush();
                $this->addFlash('success', 'Category Created Successfully');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Category Creation Failed');
            }
        }

        $num_of_elements = 5;

        if ($request->request->get('_keyword')) {
            $categories = $paginator->paginate(
                $this->categoryRepository->findBySearchTerm($request->request->get('_keyword')),
                $request->query->getInt('page', 1),
                $num_of_elements
            );
        } else {
            $categories = $paginator->paginate(
                $this->categoryRepository->findAllDesc(),
                $request->query->getInt('page', 1),
                $num_of_elements
            );
        }

        return $this->render('plugins/admin/category/index.html.twig', [
            'form'            => $form->createView(),
            'categories'      => $categories,
            'num_of_elements' => count($categories),
        ]);
    }

    /**
     * ? in this Function we can edit the category
     * ? @Route("/admin/category/edit/{id}", name="app_plugin_category_edit").
     */

    #[Route('/admin/plugin/category/edit/{id}', name: 'app_plugin_category_edit')]
    public function edit(Category $category, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->entityManager->flush();
                $this->addFlash('success', 'Category Updated Successfully');

                return $this->redirectToRoute('app_plugin_category');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Category Update Failed');

                return $this->redirectToRoute('app_plugin_category');
            }
        }

        return $this->render('plugins/admin/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * ? in this Function we can delete the category
     * ? @Route("/admin/plugin/category/delete/{id}", name="category_delete").
     */

    #[Route('/admin/plugin/category/delete/{id}', name: 'app_plugin_category_delete')]
    public function delete(Category $category): Response
    {
        try {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'Category Deleted Successfully');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Category Deletion Failed');
        }

        return $this->redirectToRoute('app_plugin_category');
    }
}

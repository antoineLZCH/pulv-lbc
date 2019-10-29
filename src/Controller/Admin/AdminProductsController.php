<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductsController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ProductRepository $repository, ObjectManager $om)
    {
        $this->repository = $repository;
        $this->om = $om;
    }


    /**
     * @Route("/admin", name="admin.product.index")
     * @return Response
     */
    public function index()
    {
        $products = $this->repository->findAll();
        return $this->render('admin/product/index.html.twig', [
            'products' => $products,
            'current_menu' => 'admin'
        ]);
    }

    /**
     * @Route("/admin/product/store", name="admin.product.store")
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->persist($product);
            $this->om->flush();
            $this->addFlash('success', 'Votre objet a bien été créé.');
            return $this->redirectToRoute('admin.product.index');
        }
        return $this->render('admin/product/store.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/product/{id}", name="admin.product.edit", methods="GET|POST")
     * @param Product $product
     * @param Request $request
     * @return Response
     */
    public function edit(Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->flush();
            $this->addFlash('success', 'Votre objet a bien été modifié.');
            return $this->redirectToRoute('admin.product.index');
        }
        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/product/{id}", name="admin.product.delete", methods="DELETE")
     * @param Product $product
     * @param Request $request
     * @return Response
     */
    public function delete(Product $product, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->get('_token'))) {
            $this->om->remove($product);
            $this->om->flush();
            $this->addFlash('success', 'Votre objet a bien été supprimé.');
        };
        return $this->redirectToRoute('admin.product.index');
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductsController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
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
     * @Route("/admin/{id}", name="admin.product.edit")
     * @param Product $product
     * @return Response
     */
    public function edit(Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}

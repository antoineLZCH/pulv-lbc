<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private $repository;

    /**
     * @Route("/products", name="products.index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('pages/products.html.twig', [
            'current_menu' => 'products'
        ]);
    }

    /**
     * @Route("/products/{slug}-{id}", name="product.show", requirements={"slug":"[a-z0-9\-]*"})
     * @param Product $product
     * @return Response
     */
    public function show(Product $product, string $slug): Response
    {
        if ($product->getSlug() !== $slug) {
            return $this->redirectToRoute('product.show', [
                'id' => $product->getId(),
                'slug' => $product->getSlug()
            ], 301);
        }
        return $this->render('pages/product.html.twig', [
            'product' => $product
        ]);
    }
}
<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/products", name="products.index")
     * @return Response
     */
    public function index(): Response
    {
        $products = $this->repository->findAll();
        return $this->render('pages/products.html.twig', [
            'current_menu' => 'products',
            'products' => $products
        ]);
    }

    /**
     * @Route("/sorted", name="products.index.sorted")
     * @return Response
     */
    public function sortedIndex(): Response
    {
        $products = $this->repository->findAndOrderByPrice();
        return $this->render('pages/sorted.html.twig', [
            'current_menu' => 'sorted',
            'products' => $products
        ]);
    }

    /**
     * @Route("/products/{slug}-{id}", name="product.show", requirements={"slug":"[a-z0-9\-]*"})
     * @param Product $product
     * @param string $slug
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
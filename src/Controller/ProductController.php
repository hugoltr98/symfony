<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Runtime\ResolverInterface;


class ProductController extends AbstractController
{
    private ProductRepository $productRepository;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->productRepository = $doctrine->getRepository(Product::class);
    }
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/create', name: 'create_product')]
    public function createProduct(ManagerRegistry $doctrine): Response
    {
        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(1999);
        $product->setDescription('Ergonomic and stylish!');
        $doctrine->persist($product);
        $doctrine->flush();

        return new Response('Saved new product with id ' . $product->getId());
    }

    #[Route('/product/{id}', name: 'product_show')]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id' . $id);
        }

        return new Response('Check out this great product: ' . $product->getName());
    }

    #[Route('/product/edit/{id}', name: 'product_edit')]
    public function update(ManagerRegistry $doctrine, int $id): Response
    {
        $product = $doctrine->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $product->setName('New product name');
        $doctrine->flush();

        return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
    }

    #[Route('/product/delete/{id}', name: 'product_delete')]
    public function delete(int $id): Response
    {
        try
        {
            $product = $this->productRepository->find($id);
            dd($product);
            $this->productRepository->remove($product, true);
            return new Response('Produit supprimé');
        }
        catch (Exception $e)
        {
            error_log($e->getMessage());
            return new Response('Une erreure est survenue');
        }
    }
}

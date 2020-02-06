<?php

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     */
    public function homepage(ProductRepository $productRepository)
    {
//        $product = new Product();
//        $product->setName('iPhone');
//        $product->setDescription('Mon produit');
//        $product->setPrice(999);
//
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($product);
//        $entityManager->flush();

    $products = $productRepository->findAllGreaterThanPrice(700);
    $favoriteProduct = $productRepository->findOneGreaterThanPrice(800);

    dump($products);

        return $this->render('index/homepage.html.twig', [
            'products' => $products,
            'favorite_product' => $favoriteProduct,
        ]);
    }
}

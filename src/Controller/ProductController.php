<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_create")
     */
    public function create(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //ajouter en bdd
            $entityManager = $this->getDoctrine()->getManager();
            //on met l'objet en attente
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'le produit a bien été ajouté.');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/product/list", name="product_list")
     */
    public function list()
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }








    /**
     * @Route("/product/modify/{id}", name="product_modify")
     */
    public function modify(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_list');
        }

        return $this->render('/product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


//    {
//        $product = new Product();
//        $form = $this->createForm(ProductType::class, $product);
//
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid())
//        {
//            $entityManager = $this->getDoctrine()->getManager();
//            $product = $entityManager->getRepository(Product::class)->find($id);
//
//            $entityManager->flush();
//
//
//            $this->addFlash('success', 'le produit a bien été modifié.');
//        }
//
//        return $this->render('product/create.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }




    /**
     * @Route("/product/delete/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager)
    {
        if ($this->isCsrfTokenValid('delete', $request->get('token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }


        return $this->redirectToRoute('product_list');
    }



//    public function delete(Request $request, $id)
//    {
//        $product = new Product();
//        $form = $this->createForm(ProductType::class, $product);
//
//        $form->handleRequest($request);
//
//            //ajouter en bdd
//            $entityManager = $this->getDoctrine()->getManager();
//            $product = $entityManager->getRepository(Product::class)->find($id);
//            $entityManager->remove($product);
//            $entityManager->flush();
//
//            $this->addFlash('success', 'le produit a bien été supprimé.');
//
//
//        return $this->redirectToRoute('product_list');
//    }











    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show($id)
    {
        dump($id);
        //SELECT * FROM table where id =?
        //on reccupere le depot qui contient nos produits
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $product = $productRepository->find($id);
//        dump($product);

        //si le produit n'existe pas
        if (!$product)
        {
            throw $this->createNotFoundException('le produit n\'existe pas.');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

}

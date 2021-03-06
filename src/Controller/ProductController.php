<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_create")
     */
    public function create(Request $request, SluggerInterface $slugger, Uploader $uploader)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $product->setSlug($slugger->slug($product->getName())->lower());

            //quand un vendeur crée un produit, on l'associe a ce produit
            if (!$product->getUser()) {
                $product->setUser($this->getUser());
            }

            /**@var UploadedFile $image*/
            //On fait l'upload de l'image
            if ($image = $form->get('image')->getData()) {
                $fileName = $uploader->upload($image);
                //mettre à jour l'entité
                $product->setImage($fileName);
            }

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
    public function modify(Request $request, Product $product, Uploader $uploader)
    {
        $this->denyAccessUnlessGranted('edit', $product);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            /**@var UploadedFile $image*/
            //On fait l'upload de l'image
            if ($image = $form->get('image')->getData()) {

                if ($product->getImage())
                {
                    $uploader->remove($product->getImage());
                }

                $fileName = $uploader->upload($image);
                //mettre à jour l'entité
                $product->setImage($fileName);
            }


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
     * @Route("/admin/product/delete/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager, Uploader $uploader)
    {
        $this->denyAccessUnlessGranted('edit', $product);

        if ($this->isCsrfTokenValid('delete', $request->get('token'))) {

           if ($product->getImage())
           {
               $uploader->remove($product->getImage());
           }

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
     * @Route("/product/{slug}", name="product_show")
     */
    public function show($slug)
    {
//        dump($id);
        //SELECT * FROM table where id =?
        //on reccupere le depot qui contient nos produits
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $product = $productRepository->findOneBy(['slug' => $slug]);
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

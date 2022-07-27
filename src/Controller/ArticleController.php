<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;


use App\Repository\ProduitRepository;
use App\Form\ProduitType;
use App\Entity\Produit;

use App\Entity\ImgProduit;

    /**
     * @Route("/article", name="article_")
     */
class ArticleController extends AbstractController
{
    
    /**
     * @Route("/", name="")
     */
    public function index(ProduitRepository $produitRepository)
    {
        $produits=$produitRepository->findAll();
        return $this->render('article/article.html.twig',["produits"=>$produits] );
    }

      /**
       * @Route("/supprimer/{id}",name="supprimer")
       */
      public function supprimer(Produit $produit): Response
      { 
                  $entityManager = $this->getDoctrine()->getManager();
                  $image=$produit->getImage();

                //   $image_nom=$this->getParameter('directory').'/'.$image;
                //   if(file_exists($image_nom))
                //   {
                //     unlink($image_nom );
                //   }
                  
                  $entityManager->remove($produit);
                  $entityManager->flush();
                  return $this->redirectToRoute('article_');
      }

   /**
     * @Route("/ajouter",name="ajouter")
     */
    public function ajouter(Request $request)
    { 
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                //detaille
                $images = $form->get('imgproduit')->getData();
                foreach($images as $image)
                {
                    $img=$image->getClientOriginalName();
                    $image->move( $this->getParameter('directory_produit'),$img );
                    $imgproduit = new ImgProduit();
                    $imgproduit->setNom($img);
                    $produit->addImgproduit($imgproduit);
                }
                //photo img:
                $image = $form->get('image')->getData();
                $img=$image->getClientOriginalName();
                $image->move( $this->getParameter('directory_produit'),$img );
                $produit = $form->getData();
                $produit->setImage($img);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($produit);
                $entityManager->flush();
                return $this->redirectToRoute('article_');
         
            }

        return $this->renderForm('modification.html.twig', ['form' => $form,"nom" => "Ajouter Produit :"
        ]);
    }

     /**
      * @Route("/editer/{id}",name="editer")
      */
      public function editer(Produit $produit,Request $request)
      { 
          $form = $this->createForm(ProduitType::class, $produit);
          $form->handleRequest($request);
              if ($form->isSubmitted() && $form->isValid()) {
        // //supprimer l image ancienne
        //             $image=$produit->getImage();
        //             $image_nom=$this->getParameter('directory').'/'.$image;
        //             if(file_exists($image_nom))
        //             {
        //               unlink($image_nom );
        //             }
        // //**************
                       //supprimer l image multiple:
if ($form->get('imgproduit')->getData()){
                       $images= $produit->getImgproduit();
                        foreach($images as $image)
                        {
                            $produit->removeImgproduit($image);
                        }

                        $images = $form->get('imgproduit')->getData();
                        foreach($images as $image)
                        {
                            $img=$image->getClientOriginalName();
                            $image->move( $this->getParameter('directory_produit'),$img );
                            $imgproduit = new ImgProduit();
                            $imgproduit->setNom($img);
                            $produit->addImgproduit($imgproduit);
                         }
            }
            
            $donnee_prod = $form->getData();
            if ($form->get('image')->getData()){
                $image = $form->get('image')->getData();
                $img=$image->getClientOriginalName();
                $image->move( $this->getParameter('directory_produit'),$img );
                $donnee_prod->setImage($img);
            }
            else
            {
                $donnee_prod->setImage($produit->getImage());
            }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($donnee_prod);
                $entityManager->flush();
                return $this->redirectToRoute('article_');
              }
          return $this->renderForm('modification.html.twig', ['form' => $form,"nom" => "Editer Article"
          ]);
      }

     /**
      * @Route("/detaille/{id}",name="detaille")
      */
    public function detaille(Produit $produit)
    { 
        return $this->render('article/detaille.html.twig',["produit"=>$produit] );
    }


}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\CategorieRepository;
use App\Form\CategorieType;
use App\Entity\Categorie;

    /**
    * @Route("/categorie", name="categorie_")
    */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="")
     */
    public function index(CategorieRepository $categorieRepository,SessionInterface $session)
    {       
        $categories=$categorieRepository->findAll();
        return $this->render('categorie/categorie.html.twig',["categories"=>$categories] );
    }

 /**
     * @Route("/ajouter",name="ajouter")
     */
    public function ajouter(Request $request)
    {        
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $image = $form->get('image')->getData();
                $img=$image->getClientOriginalName();
                $image->move( $this->getParameter('directory_categorie'),$img );
                $categorie = $form->getData();
                $categorie->setImage($img);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($categorie);
                $entityManager->flush();
                return $this->redirectToRoute('categorie_');
            }

        return $this->renderForm('modification.html.twig', ['form' => $form,"nom" => "Ajouter Categorie :"
        ]);
    }

     /**
      * @Route("/editer/{id}",name="editer")
      */
      public function editer(Categorie $categorie,Request $request)
      {     
          $form = $this->createForm(CategorieType::class, $categorie);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
        $donnee_prod = $form->getData();
                if ($form->get('image')->getData()){
                $image = $form->get('image')->getData();
                $img=$image->getClientOriginalName();
                $image->move( $this->getParameter('directory_categorie'),$img );
                $donnee_prod->setImage($img); 
            }
            else
            {
                $donnee_prod->setImage($categorie->getImage()); 
            }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($donnee_prod);
                $entityManager->flush();
                return $this->redirectToRoute('categorie_');
              }
          return $this->renderForm('modification.html.twig', ['form' => $form,"nom" => "Editer Categorie"
          ]);
      }


      /**
       * @Route("/supprimer/{id}",name="supprimer")
       */
      public function supprimer(Categorie $categorie)
      {         
                  $entityManager = $this->getDoctrine()->getManager();
                  $entityManager->remove($categorie);
                  $entityManager->flush();
                  return $this->redirectToRoute('categorie_');
       }


}


        // //supprimer l image ancienne
        //             $image=$produit->getImage();
        //             $image_nom=$this->getParameter('directory').'/'.$image;
        //             if(file_exists($image_nom))
        //             {
        //               unlink($image_nom );
        //             }

        // // $this->denyAccessUnlessGranted('ROLE_ADMIN');


        ?>
        
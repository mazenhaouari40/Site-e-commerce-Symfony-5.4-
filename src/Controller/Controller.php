<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use App\Entity\Client;
// use App\Form\ClientType;
 use App\Repository\UserRepository;
 use App\Repository\CategorieRepository;
 use App\Entity\Categorie;
 use App\Entity\Produit;
use App\Form\UserType;
use App\Form\InscriptionType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class Controller extends AbstractController
{

     /**
     * @Route("/",name="index")
     */
     public function index()
    {
        return $this->render('acceuil.html.twig');
    }
    /**
     * @Route("/listecategorie", name="listecategorie")
     */
    public function listecategorie(CategorieRepository $categorieRepository)
    {
        $categories=$categorieRepository->findAll();
        return $this->render('listecategorie.html.twig',["categories"=>$categories,] );
    }
     
    /**
    * @Route("/detaille/{id}",name="detaille")
    */
      public function detaille(Produit $produit)
      { 
          return $this->render('article/detaille.html.twig',["produit"=>$produit] );
      }
      /**
      * @Route("/produit/{id}",name="afficher_produit")
      */
      public function afficher(Categorie $categorie)
      { 
            $produits = $categorie->getProduits();
           return $this->render('article/produit.html.twig',["produits"=>$produits] );
      } 
    /**
     * @Route("/utilisateur",name="utilisateur")
     */
    public function user()
    {$this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('user.html.twig' );
    }
     /**
     * @Route("/editer",name="editer")
     */
    public function editer(Request $request,UserPasswordEncoderInterface $passwordencoder)
    { 
        $this->denyAccessUnlessGranted('ROLE_USER');
        $form = $this->createForm(InscriptionType::class, $this->getUser());
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();
                $password=$passwordencoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('utilisateur');
            }
        return $this->renderForm('modification.html.twig', ['form' => $form,"nom" => "Mon Profil :"
        ]);
    }
}?>



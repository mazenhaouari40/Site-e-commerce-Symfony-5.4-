<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use App\Entity\User;
use App\Form\ClientType;
use App\Form\InscriptionType;
use App\Form\UserType;
use App\Form\pwdType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;

    /**
     * @Route("/utilisateurs", name="utilisateurs_")
     */

class SuperAdminController extends AbstractController
{

    /**
     * @Route("/", name="")
     */
    public function index(UserRepository $UserRepository)
    {
        $utilisateurs=$UserRepository->findAll();
        return $this->render('utilisateurs.html.twig',["utilisateurs"=>$utilisateurs] );
    }

     /**
      * @Route("/editer/{id}",name="editer")
      */
    public function editer(User $user,Request $request)
    { 
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $client = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($client);
                $entityManager->flush();
                return $this->redirectToRoute('utilisateurs_');
            }
        return $this->renderForm('modification.html.twig', ['form' => $form,"nom" => "Modification"
        ]);
    }

    /**
     * @Route("/supprimer/{id}",name="supprimer")
     */
    public function supprimer(User $user)
    { 
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($user);
                $entityManager->flush();
                return $this->redirectToRoute('utilisateurs_');
    }


      /**
      * @Route("/editer_mdp/{id}",name="editer_mdp")
      */
      public function editer_mdp(User $user,Request $request,UserPasswordEncoderInterface $passwordencoder)
      { 
          $form = $this->createForm(pwdType::class, $user);

          $form->handleRequest($request);
              if ($form->isSubmitted() && $form->isValid()) {
                  $client = $form->getData();

                  $password=$passwordencoder->encodePassword($user,$user->getPassword());
                  $user->setPassword($password);

                  $entityManager = $this->getDoctrine()->getManager();
                  $entityManager->persist($client);
                  $entityManager->flush();
                  return $this->redirectToRoute('utilisateurs_');
              }
          return $this->renderForm('modification.html.twig', ['form' => $form,"nom" => "Modifier le mot de passe :"
          ]);
      }

     /**
     * @Route("/ajouter",name="ajouter")
     */
    public function ajouter(Request $request,UserPasswordEncoderInterface $passwordencoder)
    { 
        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();
                $password=$passwordencoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('utilisateurs_');
            }
        return $this->renderForm('modification.html.twig', ['form' => $form,"nom" => "Ajouter User :"
        ]);
    }

}
?>



 
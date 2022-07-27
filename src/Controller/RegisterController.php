<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\InscriptionType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{

    /**
     * @Route("/register", name="app_register")
     */

    public function register(Request $request,UserPasswordEncoderInterface $passwordencoder)
    {
        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();

                $user = $form->getData();
                $password=$passwordencoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
                
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_login');
            }
        return $this->renderForm('modification.html.twig', ['form' => $form,"nom" => "Inscription"
        
    
    ]);
    }
}

<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Form\CommandeType;
use App\Entity\ComProduit;    
use App\Entity\Commande;    

use Symfony\Component\HttpFoundation\Request;

    /**
     * @Route("/commande", name="commande_")
     */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="")
     */
    public function index(CommandeRepository $commandeRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $commande=$commandeRepository->findAll();
        return $this->render('listecommande.html.twig',["commandes"=>$commande] );
    }
    /**
     * @Route("/ajouter", name="ajouter")
     */
    public function AjouterCommmande(SessionInterface $session,ProduitRepository $ProduitRepository,Request $request)
    {   
        $this->denyAccessUnlessGranted('ROLE_USER');
        // $nbproduit=$session->get('nbproduit',0);
        // dd($time);
        $time = new \DateTime();
        $user=$this->getUser();
        $total=0;
        $panier=$session->get('panier',[]);
        $commande = new Commande();
        $commande->setUser($user);
        $commande->setTotal($total);
        $commande->setDate($time);
        $commande->setStatut("non traite");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);
        $entityManager->flush();
        foreach($panier as $id =>$quantite )
        {
            $produit=$ProduitRepository->find($id);
            $commande_produit = new ComProduit();
            $commande_produit->setProduit($produit);
            $commande_produit->setQuantite($quantite);
            $commande_produit->setCommande($commande);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande_produit);
            $entityManager->flush();
            $total=$total+$produit->getPrix()*$quantite;
            unset($panier[$id]);
        }
        $commande->setTotal($total);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);
        $entityManager->flush();        
        $session->set('nbproduit',0);
        $session->set('panier',[]);
        return $this->redirectToRoute('listecategorie');
    }


 /**
      * @Route("/editer/{id}",name="editer")
      */
      public function editer(Commande $commande,Request $request)
      { 
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
          $form = $this->createForm(CommandeType::class, $commande);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) 
          {
            $donnee_commande = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($donnee_commande);
            $entityManager->flush();
            return $this->redirectToRoute('commande_');
            }
          return $this->renderForm('modification.html.twig', ['form' => $form,"nom" => "Editer Commande :"
          ]);
      }

}


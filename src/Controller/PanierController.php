<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\ProduitType;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;

    /**
    * @Route("/panier", name="panier_")
    */

class PanierController extends AbstractController
{
    /**
     * @Route("/", name="")
     */
    public function index(SessionInterface $session,ProduitRepository $ProduitRepository)
    {
        $panier=$session->get('panier',[]);
        $panier_donnee=[];
        foreach($panier as $id =>$quantite )
        {
            $panier_donnee[]=[
            'produit' => $ProduitRepository->find($id),
            'quantite' =>$quantite];
        }
        $total=0;
        foreach($panier_donnee as $produit)
        {
            $total= $total + $produit['produit']->getPrix()*$produit['quantite'];
        }
        // dd($panier_donnee);
        return $this->render('panier/panier.html.twig',['produits'=>$panier_donnee,'total'=>$total]);
    }

    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation(SessionInterface $session,ProduitRepository $ProduitRepository)
    {
        $panier=$session->get('panier',[]);
        $panier_donnee=[];
        foreach($panier as $id =>$quantite )
        {
            $panier_donnee[]=[
            'produit' => $ProduitRepository->find($id),
            'quantite' =>$quantite];
        }
        $total=0;
        foreach($panier_donnee as $produit)
        {
            $total= $total + $produit['produit']->getPrix()*$produit['quantite'];
        }
        return $this->render('panier/confirmation.html.twig',['produits'=>$panier_donnee,'total'=>$total]);
    }



    /**
     * @Route("/ajouter/{id}", name="ajouter")
     */

    public function add(int $id,SessionInterface $session,Request $request)
    {
        $qte=$request->request->get('qte');

        $panier=$session->get('panier',[]);
        $nbproduit=$session->get('nbproduit',0);
        if (!empty($panier[$id]))
        {
            $panier[$id]=$panier[$id]+$qte;
        }
        else
        {
            $panier[$id]=$qte;
        }
        $nbproduit=$nbproduit+$qte;

        $session->set('panier',$panier);
        $session->set('nbproduit',$nbproduit);
        return $this->redirectToRoute('listecategorie');
    }


    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(int $id,SessionInterface $session)
    {
        $panier=$session->get('panier',[]);
        $nbproduit=$session->get('nbproduit',0);
        $nbproduit=$nbproduit-$panier[$id];
        unset($panier[$id]);
        $session->set('nbproduit',$nbproduit);
        $session->set('panier',$panier);
        return $this->redirectToRoute('panier_');
    }
}

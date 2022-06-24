<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuteurController extends AbstractController
{
    #[Route('/auteurs', name: 'app_auteurs')]
    public function allAuteurs(ManagerRegistry $doctrine): Response
    {
        $auteurs = $doctrine->getRepository(Auteur::class)->findAll();
       //dd($auteurs);
        return $this->render('auteur/allAuteurs.html.twig', [
            'auteurs' => $auteurs,
        ]);
    }
    
/**
 * @Route("ajout-auteur", name="auteur_ajout")
 */

 public function ajout(ManagerRegistry $doctrine, Request $request)
 {
    $auteur = new Auteur();
        // on crée le form en liant le FormType à l'objet crée
    $form=$this->createForm(AuteurType::class, $auteur);
        //on donne accés aux données du form pour validation des données
    $form->handleRequest($request);
        // si le formulaire est soumis et validé
    if ($form->isSubmitted()&& $form->isValid())
    {
        //on récupère le manager de doctrine
        $manager = $doctrine->getManager();
        // on persist l'objet
        $manager->persist($auteur);
        // puis envoie en bdd
        $manager->flush();
        return $this->redirectToRoute("app_auteurs");
    }

  return $this->render("auteur/formulaire.html.twig", [
      'formAuteur'=> $form->createView()
  ]);
}

/**
 * @Route ("/update-auteur/{id<\d+>}", name="auteur_update" )// $id aura comme valeur l'id passé en paramètre dans la route
 */
public function update(ManagerRegistry $doctrine, $id, Request $request)
{
    //on recupére l'auteur dont l'id est celui passé en paramètre de la fonction 
    $auteur =$doctrine->getRepository(Auteur::class)->find($id);
    //dd($auteur);
    // on crée le form en liant le FormType à l'objet crée
    $form=$this->createForm(AuteurType::class, $auteur);
    //on donne accés aux données du form pour validation des données
    $form->handleRequest($request);
    // si le formulaire est soumis et validé
        if ($form->isSubmitted() && $form->isValid())
    {
         $manager = $doctrine->getManager();
        // on persist l'objet
        $manager->persist($auteur);
        // puis envoie en bdd
        $manager->flush();

        return $this->redirectToRoute("app_auteurs");
    }

    return $this->render("auteur/formulaire.html.twig",[
            'formAuteur' => $form->createView()
        ]);
}
/**
 * @Route("/delete_auteur_{id<\d+>}", name="auteur_delete") 
 */
public function delete($id, ManagerRegistry $doctrine){
    //on recupére l'auteur à supprimer
    $auteur =$doctrine->getRepository(Auteur::class)->find($id);
    // on recupére le manager de doctrine
    $manager =$doctrine->getManager(); 
    // on prepare la suppression de l'auteur
    $manager->remove($auteur);
    //on execute la suppression dans la BDD
    $manager->flush();
        
    return $this->redirectToRoute("app_auteurs");
}

/**
 * @Route ("/auteur_{id<\d+>}", name="app_auteur")
 */
public function show($id, ManagerRegistry $doctrine){

    //on recupére l'auteur donc l'id est celui passé en paramètre de la fonction 
 
     $auteur =$doctrine->getRepository(Auteur::class)->find($id);
   
     return $this->render("auteur/unAuteur.html.twig", [
        'auteur'=>$auteur
     ]);

}

}

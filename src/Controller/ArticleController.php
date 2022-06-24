<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function allArticles(ManagerRegistry $doctrine): Response
    {
    $articles = $doctrine->getRepository(Article::class)->findAll();
    //dd($articles);
        return $this->render('article/allArticles.html.twig', [
            'articles' => $articles,
        ]);    
      }


/** 
 * @Route("ajout-article", name="article_ajout")
*/

public function ajout(ManagerRegistry $doctrine, Request $request)
{
    //on crée un objet article
    $article = new Article();
    // on crée le form en liant le FormType à l'objet crée
    $form=$this->createForm(ArticleType::class, $article);
    //on donne accés aux données du form pour validation des données
    $form->handleRequest($request);
    // si le formulaire est soumis et validé
    if ($form->isSubmitted() && $form->isValid())
    {
        // je m'occupe d'affecter les données manquantes (qui ne parviennent pas
        //du formulaire)
        $article->setDateDeCreation(new DateTime("now"));
        //on récupère le manager de doctrine
        $manager = $doctrine->getManager();
        // on persist l'objet
        $manager->persist($article);
        // puis envoie en bdd
        $manager->flush();

        return $this->redirectToRoute("app_articles");
    } 
        return $this->render("article/formulaire.html.twig",[
            'formArticle' => $form->createView()
        ]);
       
}

/**
 * @Route("/update-article/{id<\d+>}", name="article_update")
 */
 public function update(ManagerRegistry $doctrine, $id, Request $request)// $id aura comme valeur l'id passé en paramètre dans la route
 {
     //on recupére l'article dont l'id est celui passé en paramètre de la fonction 
    $article = $doctrine->getRepository(Article::class)->find($id);
    //dd($article);
    
    // on crée le form en liant le FormType à l'objet crée
    $form=$this->createForm(ArticleType::class, $article);
    //on donne accés aux données du form pour validation des données
    $form->handleRequest($request);
    // si le formulaire est soumis et validé
    if ($form->isSubmitted() && $form->isValid())
    {
        // je m'occupe d'affecter les données manquantes (qui ne parviennent pas
        //du formulaire)
        $article->setDateDeModification(new DateTime("now"));
        //on récupère le manager de doctrine
        $manager = $doctrine->getManager();
        // on persist l'objet
        $manager->persist($article);
        // puis envoie en bdd
        $manager->flush();

        return $this->redirectToRoute("app_articles");
    } 
        return $this->render("article/formulaire.html.twig",[
            'formArticle' => $form->createView()
        ]);
 }

/**
 * @Route("/delete_article_{id<\d+>}", name="article_delete")
 */

public function delete($id, ManagerRegistry $doctrine){
// on recupére l'article à supprimer
    $article = $doctrine->getRepository(Article::class)->find($id);
// on recupére le manager de doctrine
$manager =$doctrine->getManager(); 
// on prepare la suppression de l'article
$manager->remove($article);
//on execute la suppression dans la BDD
$manager->flush();
    
return $this->redirectToRoute("app_articles");
}

/**
 * @Route ("/article_{id<\d+>}", name="app_article")
 */
public function show($id, ManagerRegistry $doctrine)// $id aura comme valeur l'ide passé en paramètre dans la route
 {
     //on recupére l'article donc l'id est celui passé en paramètre de la fonction 
 
     $article =$doctrine->getRepository(Article::class)->find($id);
   
     return $this->render("article/unArticle.html.twig", [
        'article'=>$article
     ]);
 }


}

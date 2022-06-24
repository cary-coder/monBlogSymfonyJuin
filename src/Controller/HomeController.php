<?php

namespace App\Controller;

use App\Entity\Article;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(ManagerRegistry $doctrine): Response
    {
        $dernierArticle = $doctrine->getRepository(Article::class)->findOneBy([],
        ["dateDeCreation"=> "DESC"]);
        //dd($dernierArticle);
        return $this->render('home/index.html.twig', [
            'dernierArticle'=> $dernierArticle]);
    }
}

<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findBy([],['dateAdd' => 'ASC'],10,3),
        ]);
    }

    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function lastArticle(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([],['dateAdd' => 'DESC'], 3);

        return $this->render('article/_last_article.html.twig', [
            'articles' => $articles,
        ]);
    }


    /**
     * @return Response
     */
    #[Route('/contact', name: 'contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }
}

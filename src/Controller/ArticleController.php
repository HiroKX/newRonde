<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Attachment;
use App\Entity\Type;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\AlertServiceInterface;
use App\Service\FileUploadServiceInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    private FileUploadServiceInterface $uploaderService;
    private EntityManagerInterface $entityManager;
    private AlertServiceInterface $alertService;

    /**
     * @param FileUploadServiceInterface $uploaderService
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     */
    public function __construct(FileUploadServiceInterface $uploaderService, EntityManagerInterface $entityManager, AlertServiceInterface $alertService)
    {
        $this->uploaderService = $uploaderService;
        $this->entityManager = $entityManager;
        $this->alertService = $alertService;
    }

    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     * @throws ORMException
     */
    #[Route('/reglement', name: 'article_reg', methods: ['GET'])]
    public function reglement(ArticleRepository $articleRepository): Response
    {
        return $this->showArticle(Type::CODE_REGLEMENT, $articleRepository);
    }

    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     * @throws ORMException
     */
    #[Route('/etalonnage', name: 'article_eta', methods: ['GET'])]
    public function etalonnage(ArticleRepository $articleRepository): Response
    {
        return $this->showArticle(Type::CODE_ETALONNAGE, $articleRepository);
    }

    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     * @throws ORMException
     */
    #[Route('/engagement', name: 'article_eng', methods: ['GET'])]
    public function engagement(ArticleRepository $articleRepository): Response
    {
        return $this->showArticle(Type::CODE_ENGAGEMENT, $articleRepository);
    }

    /**
     * @param string $codeType
     * @param ArticleRepository $articleRepository
     * @return Response
     * @throws ORMException
     */
    private function showArticle(string $codeType, ArticleRepository $articleRepository): Response
    {
        $type = $this->entityManager->getReference(Type::class, $codeType);
        $article = $articleRepository->findOneBy(['type' => $type],['dateAdd'=>'DESC']);

        if (!$article) {
            $this->alertService->info(sprintf('Aucun article de type "%s" vous avez été rediriger vers la page d\'acceuil.', $type->getNom()));
            return $this->redirectToRoute('index');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @param Article $article
     * @return Response
     */
    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/load/ajax', name:'ajax_article', methods:['POST'], options: ['expose' => true])]
    public function loadArticle(Request $request):Response {
        $articleRepository= $this->entityManager->getRepository(Article::class);

        if ($request->isXmlHttpRequest()) {
            $offset = $request->get('offset');
            $types = $request->get('type');
            $cond = [];
            if(!is_null($types) && $types != 'all'){
                $cond['type']=$types;
            }
            $articles = $articleRepository->findBy($cond,['dateAdd' => 'DESC'],10,(10*$offset));
            return $this->render('article/ajax_article.html.twig',['articles'=>$articles,'typ'=>$types]);
        }else{
            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }
    }
}

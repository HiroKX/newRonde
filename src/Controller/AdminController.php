<?php

namespace App\Controller;

use App\Repository\ArchiveRepository;
use App\Repository\ArticleRepository;
use App\Repository\TypeRepository;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[ROUTE('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private AlertServiceInterface $alertService;

    public function __construct(EntityManagerInterface $entityManager,AlertServiceInterface $alertService){
        $this->entityManager = $entityManager;
        $this->alertService = $alertService;
    }

    #[ROUTE('/',name:'admin_index')]
    public function indexAdmin(): Response
    {
        return $this->render('admin/index.html.twig',[]);
    }

    #[ROUTE('/articles',name:'admin_articles')]
    public function adminArticles(ArticleRepository $articleRepository):Response
    {

        return $this->render('admin/Articles.html.twig',[
            'articles' => $articleRepository->findBy([],['dateAdd' => 'DESC'])
        ]);
    }

    #[ROUTE('/archives',name:'admin_archives')]
    public function adminArchives(ArchiveRepository $archiveRepository):Response
    {


        return $this->render('admin/Archives.html.twig',[
            'archives' => $archiveRepository->findAll()
        ]);
    }

    #[ROUTE('/types',name:'admin_types')]
    public function adminTypes(TypeRepository $typeRepository):Response
    {

        return $this->render('admin/Types.html.twig',[
            'types' => $typeRepository->findAll()
        ]);
    }


}
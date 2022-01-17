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
     * @param Request $request
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadAttachment($form->get('attachments')->getData(), $article);
            $this->uploadImageGallery($form->get('images')->getData(), $article);

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            $this->alertService->success('Article créer');

            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
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

    /**
     * @param Request $request
     * @param Article $article
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadAttachment($form->get('attachments')->getData(), $article);
            $this->uploadImageGallery($form->get('images')->getData(), $article);

            $this->entityManager->flush();

            $this->alertService->success('Article modifié');

            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }


    #[Route('/load/ajax', name:'ajax_article', methods:['POST'], options: ['expose' => true])]
    public function loadArticle(Request $request):Response {
        $articleRepository= $this->entityManager->getRepository(Article::class);

        if ($request->isXmlHttpRequest()) {
            $offset = $request->get('offset');
            $articles = $articleRepository->findBy([],['dateAdd' => 'DESC'],10,(10*$offset)+3);
            return $this->render('article/ajax_article.html.twig',['articles'=>$articles]);
        }else{
            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @param string $pathAttachmentArticle
     * @param Attachment $attachment
     * @return Response
     */
    #[Route('/download/attachment/{id}', name: 'article_download_attachment')]
    public function downloadAttachment(string $pathAttachmentArticle, Attachment $attachment): Response
    {
        return $this->file($pathAttachmentArticle . $attachment->getFilename());
    }

    /**
     * @param Article $article
     * @param Attachment $attachment
     * @param Request $request
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/delete/attachment/{article}/{attachment}', name: 'article_delete_attachment')]
    public function deleteAttachment(Article $article, Attachment $attachment, Request $request): Response
    {
        $article->removeImage($attachment);
        $this->entityManager->remove($attachment);
        $this->entityManager->flush();

        $this->uploaderService->delete($attachment);

        $this->alertService->success('Image de la gallerie supprimée');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     * @param Article $article
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $attachments = $article->getAttachments();
            foreach ($attachments as $attachment) {
                $this->uploaderService->delete($attachment);

                $article->removeAttachment($attachment);
                $this->entityManager->remove($attachment);
                $this->entityManager->flush();
            }

            $images = $article->getImages();
            foreach ($images as $image) {
                $this->uploaderService->delete($image);

                $article->removeImage($image);
                $this->entityManager->remove($image);
                $this->entityManager->flush();
            }

            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }

        $this->alertService->success('Article supprimé');

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param Collection $attachments
     * @param Article $article
     * @return void
     */
    private function uploadAttachment(Collection $attachments, Article $article): void
    {
        foreach ($attachments as $attachment) {
            /** @var UploadedFile $imageUploadedFile */
            $imageUploadedFile = $attachment->getFile();

            if ($imageUploadedFile) {
                $attachmentObject = $this->uploaderService->upload($attachment);
                $article->addAttachment($attachmentObject);
            }
        }
    }


    /**
     * @param array $images
     * @param Article $article
     * @return void
     */
    private function uploadImageGallery(array $images, Article $article): void
    {
        foreach ($images as $image) {
            $attachment = new Attachment();
            $attachment->setFile($image);

            $attachmentObject = $this->uploaderService->upload($attachment);
            $article->addImage($attachmentObject);
        }
    }
}

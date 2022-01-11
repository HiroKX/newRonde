<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Attachment;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\FileUploadServiceInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    private FileUploadServiceInterface $uploaderService;
    private EntityManagerInterface $entityManager;

    /**
     * @param FileUploadServiceInterface $uploaderService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(FileUploadServiceInterface $uploaderService, EntityManagerInterface $entityManager)
    {
        $this->uploaderService = $uploaderService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/', name: 'article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/', name: 'article_reg', methods: ['GET'])]
    public function reglement(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/', name: 'article_eta', methods: ['GET'])]
    public function etalonnage(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/', name: 'article_eng', methods: ['GET'])]
    public function engagement(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
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

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
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

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
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

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/delete/attachment/{article}/{attachment}', name: 'article_delete_attachment')]
    public function deleteAttachment(Article $article, Attachment $attachment, Request $request): Response
    {
        $article->removeImage($attachment);
        $this->entityManager->remove($attachment);
        $this->entityManager->flush();

        $this->uploaderService->delete($attachment);

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
            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
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

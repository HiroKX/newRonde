<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Attachments;
//use App\Entity\Images;
use App\Form\ArticleType;

use App\Service\FileUploadService;
use App\Repository\ArticleRepository;
use App\Service\FileUploadServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\RuntimeException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
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
     * @param EntityManagerInterface $entityManager
     * @param FileUploadServiceInterface $uploaderService
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploadServiceInterface $uploaderService): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $attachs = $form->get('attachments')->getData();
            foreach ($attachs as $attach) {
                $attachment = $uploaderService->upload($attach);
                $article->addAttachment($attachment);
            }

            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $attachment = $uploaderService->upload($image);
                $article->addImage($attachment);
            }

            $entityManager->persist($article);
            $entityManager->flush();

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
     * @param EntityManagerInterface $entityManager
     * @param FileUploadServiceInterface $uploaderService
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, FileUploadServiceInterface $uploaderService): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $attachs = $form->get('attachments')->getData();
            foreach ($attachs as $attach) {
                if ($attach) {
                    $attachment = $uploaderService->upload($attach);
                    $article->addAttachment($attachment);
                }
            }

            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                if ($image) {
                    $imageUpload = $uploaderService->upload($image);
                    $article->addImage($imageUpload);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @param string $pathAttachmentArticle
     * @param Attachments $attachment
     * @return Response
     */
    #[Route('/download/attachment/{id}', name: 'article_download_attachment')]
    public function downloadAttachment(string $pathAttachmentArticle, Attachments $attachment): Response
    {
        return $this->file($pathAttachmentArticle . $attachment->getFilename());
    }


    /**
     * @param Request $request
     * @param Article $article
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }
}

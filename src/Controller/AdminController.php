<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Attachment;
use App\Form\ArticleType;
use App\Repository\ArchiveRepository;
use App\Repository\ArticleRepository;
use App\Repository\TypeRepository;
use App\Service\AlertServiceInterface;
use App\Service\FileUploadServiceInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Created by HiroKX
 * 18/Jan/2022
 */
#[ROUTE('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{

    /**
     * @var FileUploadServiceInterface
     */
    private FileUploadServiceInterface $uploaderService;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var AlertServiceInterface
     */
    private AlertServiceInterface $alertService;

    /**
     * @param FileUploadServiceInterface $uploaderService
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     */
    public function __construct(FileUploadServiceInterface $uploaderService, EntityManagerInterface $entityManager, AlertServiceInterface $alertService){
        $this->entityManager = $entityManager;
        $this->alertService = $alertService;
        $this->uploaderService = $uploaderService;
    }

    /**
     * @return Response
     */
    #[ROUTE('/',name:'admin_index')]
    public function indexAdmin(): Response
    {
        return $this->render('admin/index.html.twig',[]);
    }

    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[ROUTE('/articles',name:'admin_articles')]
    public function adminArticles(ArticleRepository $articleRepository):Response
    {

        return $this->render('admin/Articles.html.twig',[
            'articles' => $articleRepository->findBy([],['dateAdd' => 'DESC'])
        ]);
    }

    /**
     * @param ArchiveRepository $archiveRepository
     * @return Response
     */
    #[ROUTE('/archives',name:'admin_archives')]
    public function adminArchives(ArchiveRepository $archiveRepository):Response
    {


        return $this->render('admin/Archives.html.twig',[
            'archives' => $archiveRepository->findAll()
        ]);
    }

    /**
     * @param TypeRepository $typeRepository
     * @return Response
     */
    #[ROUTE('/types',name:'admin_types')]
    public function adminTypes(TypeRepository $typeRepository):Response
    {

        return $this->render('admin/Types.html.twig',[
            'types' => $typeRepository->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @return Response
     */
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

    /**
     * @param Article $article
     * @param Attachment $attachment
     * @param Request $request
     * @return Response
     */
    #[Route('/article/delete/attachment/{article}/{attachment}', name: 'article_delete_attachment')]
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
    #[Route('/article/{id}', name: 'article_delete', methods: ['POST'])]
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
     * @param Request $request
     * @return Response
     */
    #[Route('/articles/new', name: 'article_new', methods: ['GET', 'POST'])]
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
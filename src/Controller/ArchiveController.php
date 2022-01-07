<?php

namespace App\Controller;

use App\Entity\Archive;
use App\Form\ArchiveType;
use App\Repository\ArchiveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/archive')]
class ArchiveController extends AbstractController
{
    #[Route('/', name: 'archive_index', methods: ['GET'])]
    public function index(ArchiveRepository $archiveRepository): Response
    {
        return $this->render('archive/index.html.twig', [
            'archives' => $archiveRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'archive_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $archive = new Archive();
        $form = $this->createForm(ArchiveType::class, $archive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($archive);
            $entityManager->flush();

            return $this->redirectToRoute('archive_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('archive/new.html.twig', [
            'archive' => $archive,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'archive_show', methods: ['GET'])]
    public function show(Archive $archive): Response
    {
        return $this->render('archive/show.html.twig', [
            'archive' => $archive,
        ]);
    }

    #[Route('/{id}/edit', name: 'archive_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Archive $archive, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArchiveType::class, $archive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('archive_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('archive/edit.html.twig', [
            'archive' => $archive,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'archive_delete', methods: ['POST'])]
    public function delete(Request $request, Archive $archive, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$archive->getId(), $request->request->get('_token'))) {
            $entityManager->remove($archive);
            $entityManager->flush();
        }

        return $this->redirectToRoute('archive_index', [], Response::HTTP_SEE_OTHER);
    }
}

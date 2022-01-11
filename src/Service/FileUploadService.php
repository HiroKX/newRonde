<?php

namespace App\Service;

use App\Entity\Attachments;
//use App\Entity\Images;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadService implements FileUploadServiceInterface
{
    private string $targetDirectory;
    private SluggerInterface $slugger;
    private EntityManagerInterface $entityManager;

    /**
     * @param string $targetDirectory
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(string $targetDirectory, SluggerInterface $slugger, EntityManagerInterface $entityManager)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function upload(UploadedFile $file): Attachments
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid('', true).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);

            $attachments = new Attachments();
            $attachments->setOriginalFilename($originalFilename);
            $attachments->setFilename($fileName);
            $attachments->setTaille($this->getSize($fileName));

            $this->entityManager->persist($attachments);
            $this->entityManager->flush();
        } catch (FileException $e) {
            throw new \RuntimeException('Error during file upload '.$e->getMessage() . ':' . $e->getTraceAsString());
        }

        return $attachments;
    }

    /**
     * @inheritDoc
     */
    public function delete(Attachments $attach): bool
    {
            return unlink($this->getTargetDirectory().$attach->getFilename());
    }

    /**
     * @return mixed
     */
    private function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * @param string $filename
     * @return int
     */
    #[Pure]
    private function getSize(string $filename): int
    {
        return filesize($this->getTargetDirectory().'/'.$filename);
    }
}

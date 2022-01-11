<?php

namespace App\Service;

use App\Entity\Attachment;
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
    public function upload(Attachment $attachment): Attachment
    {
        /** @var UploadedFile $file */
        $file = $attachment->getFile();

        if (!$file) {
            throw new \RuntimeException('Error image file.');
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid('', true).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);

            $attachment->setOriginalFilename($originalFilename);
            $attachment->setFilename($fileName);
            $attachment->setTaille($this->getSize($fileName));

            $this->entityManager->persist($attachment);
            $this->entityManager->flush();
        } catch (FileException $e) {
            throw new \RuntimeException('Error during file upload '.$e->getMessage() . ':' . $e->getTraceAsString());
        }

        return $attachment;
    }

    /**
     * @inheritDoc
     */
    public function delete(Attachment $attach): bool
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

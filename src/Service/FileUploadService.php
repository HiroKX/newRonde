<?php

namespace App\Service;

use App\Entity\Attachments;
use App\Entity\Images;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadService implements FileUploadServiceInterface
{
    private string $targetDirectory;
    private SluggerInterface $slugger;

    /**
     * @param string $targetDirectory
     * @param SluggerInterface $slugger
     */
    public function __construct(string $targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    /**
     * @param UploadedFile $file
     * @return Attachments
     */
    public function upload(UploadedFile $file): Attachments
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $filename = $safeFilename.'-'.uniqid('', true).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $filename);

            $attach = new Attachments();
            $attach->setOriginalFilename($originalFilename);
            $attach->setFilename($filename);
            $attach->setTaille($this->getSize($filename));
        } catch (FileException $e) {
            throw new \RuntimeException('Error during file upload '.$e->getMessage() . ':' . $e->getTraceAsString());
        }

        return $attach;
    }

    /**
     * @return string
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

    public function uploadImage(UploadedFile $file): Images
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $filename = $safeFilename.'-'.uniqid('', true).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $filename);
            $image = new Images();
            $image->setOriginalFilename($originalFilename);
            $image->setNom($filename);
            $image->setTaille($this->getSize($filename));
        } catch (FileException $e) {
            throw new \RuntimeException('Error during file upload '.$e->getMessage() . ':' . $e->getTraceAsString());
        }

        return $image;
    }
}

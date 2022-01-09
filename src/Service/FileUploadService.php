<?php

namespace App\Service;

use App\Entity\Attachments;
use App\Entity\Images;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadService implements FileUploadServiceInterface
{
    private $targetDirectory;
    private $slugger;

    /**
     * @param $targetDirectory
     * @param SluggerInterface $slugger
     */
    public function __construct($targetDirectory, SluggerInterface $slugger)
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
        $fileName = $safeFilename.'-'.uniqid('', true).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
            $attach = new Attachments();
            $attach->setOriginalFilename($originalFilename);
            $attach->setFilename($fileName);
            $attach->setTaille($this->getSize($this->getTargetDirectory().'/'.$fileName));
        } catch (FileException $e) {
            throw new \RuntimeException('Error during file upload '.$e->getMessage() . ':' . $e->getTraceAsString());
        }

        return $attach;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function uploadImage(UploadedFile $file): Images
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid('', true).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
            $img = new Images();
            $img->setOriginalFilename($originalFilename);
            $img->setNom($fileName);
            $img->setTaille($this->getSize($this->getTargetDirectory().'/'.$fileName));
        } catch (FileException $e) {
            throw new \RuntimeException('Error during file upload '.$e->getMessage() . ':' . $e->getTraceAsString());
        }
        return $img;
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

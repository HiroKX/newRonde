<?php

namespace App\Service;

use App\Entity\Attachments;
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
            $attach->setNom($fileName);
            $attach->setTaille(filesize($this->getTargetDirectory().'/'.$fileName));
        } catch (FileException $e) {
            throw new \RuntimeException('Error during file upload '.$e->getMessage() . ':' . $e->getTraceAsString());
        }

        return $attach;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

}

<?php

namespace App\Service;

use App\Entity\Attachments;
//use App\Entity\Images;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploadServiceInterface
{
    /**
     * @param UploadedFile $file
     * @return Attachments
     */
    public function upload(UploadedFile $file): Attachments;
    //public function uploadImage(UploadedFile $image): Images;
}

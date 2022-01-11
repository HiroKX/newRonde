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

    /**
     * @param Attachments $attach
     * @return bool
     */
    public function delete(Attachments $attach): bool;
}

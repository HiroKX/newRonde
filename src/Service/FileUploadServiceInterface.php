<?php

namespace App\Service;

use App\Entity\Attachment;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploadServiceInterface
{
    /**
     * @param Attachment $attachment
     * @return Attachment
     */
    public function upload(Attachment $attachment): Attachment;

    /**
     * @param Attachment $attach
     * @return bool
     */
    public function delete(Attachment $attach): bool;
}

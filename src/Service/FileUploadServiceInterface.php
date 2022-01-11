<?php

namespace App\Service;

use App\Entity\Attachment;

interface FileUploadServiceInterface
{
    /**
     * @param Attachment $attachment
     * @return Attachment
     */
    public function upload(Attachment $attachment): Attachment;

    /**
     * @param Attachment $attachment
     * @return bool
     */
    public function delete(Attachment $attachment): bool;
}

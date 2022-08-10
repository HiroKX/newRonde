<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
class Attachment{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id = 0;

    #[ORM\Column(type: 'string', length: 255)]
    private string $originalFilename;

    #[ORM\Column(type: 'string', length: 255)]
    private string $filename;

    #[ORM\Column(type: 'integer')]
    private int $taille;

    private ?UploadedFile $file = null;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'files')]
    private $articleFiles;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'images')]
    private $articleImages;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'imagesGallery')]
    private $articleImagesGallery;


    public function setId(int $id):void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    /**
     * @param string $originalFilename
     * @return $this
     */
    public function setOriginalFilename(string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTaille(): ?int
    {
        return $this->taille;
    }

    /**
     * @param int $taille
     * @return $this
     */
    public function setTaille(int $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * @return UploadedFile|null
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     * @return $this
     */
    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getArticleFiles(): ?Article
    {
        return $this->articleFiles;
    }

    public function setArticleFiles(?Article $articleFiles): self
    {
        $this->articleFiles = $articleFiles;

        return $this;
    }

    public function getArticleImages(): ?Article
    {
        return $this->articleImages;
    }

    public function setArticleImages(?Article $articleImages): self
    {
        $this->articleImages = $articleImages;

        return $this;
    }

    public function getArticleImagesGallery(): ?Article
    {
        return $this->articleImagesGallery;
    }

    public function setArticleImagesGallery(?Article $articleImagesGallery): self
    {
        $this->articleImagesGallery = $articleImagesGallery;

        return $this;
    }


}

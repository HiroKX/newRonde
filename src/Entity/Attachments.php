<?php

namespace App\Entity;

use App\Repository\AttachmentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

#[ORM\Entity(repositoryClass: AttachmentsRepository::class)]
class Attachments{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $originalFilename;

    #[ORM\Column(type: 'string', length: 255)]
    private $filename;

    #[ORM\Column(type: 'integer')]
    private $taille;

    public function __construct()
    {
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
     * @return Article|null
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * @param Article|null $article
     * @return $this
     */
    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

}

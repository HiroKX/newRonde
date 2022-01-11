<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $titre;

    #[ORM\Column(type: 'string', length: 255)]
    private $utitre;

    #[ORM\Column(type: 'text')]
    private $contenu;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private $type;

    #[ORM\ManyToOne(targetEntity: Archive::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private $annee;

    #[ManyToMany(targetEntity: Attachments::class,cascade: ["remove"])]
    #[JoinTable(name: "article_file_attachment")]
    #[JoinColumn(name: "article_id", referencedColumnName: "id")]
    #[InverseJoinColumn(name: "attachments_id", referencedColumnName: "id")]
    private $attachments;

    #[ManyToMany(targetEntity: Attachments::class,cascade: ["remove"])]
    #[JoinTable(name: "article_image_attachment")]
    #[JoinColumn(name: "article_id", referencedColumnName: "id")]
    #[InverseJoinColumn(name: "attachments_id", referencedColumnName: "id")]
    private $images;


    #[ORM\Column(type: 'datetime')]
    private $dateAdd;

    public function __construct()
    {
        $this->dateAdd = new \DateTime();
        $this->attachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getUtitre(): ?string
    {
        return $this->utitre;
    }

    public function setUtitre(string $utitre): self
    {
        $this->utitre = $utitre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getType(): ?type
    {
        return $this->type;
    }

    public function setType(?type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAnnee(): ?archive
    {
        return $this->annee;
    }

    public function setAnnee(?archive $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    public function setDateAdd(\DateTimeInterface $dateAdd): self
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachments $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
        }

        return $this;
    }

    public function removeAttachment(Attachments $image): self
    {
        $this->images->removeElement($image);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getImages(): Collection
    {
        return $this->attachments;
    }

    public function addImage(Attachments $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
        }

        return $this;
    }

    public function removeImage(Attachments $images): self
    {
        $this->images->removeElement($images);

        return $this;
    }



}

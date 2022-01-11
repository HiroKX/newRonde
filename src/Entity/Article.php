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
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $titre;

    #[ORM\Column(type: 'string', length: 255)]
    private string $utitre;

    #[ORM\Column(type: 'text')]
    private string $contenu;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private Type $type;

    #[ORM\ManyToOne(targetEntity: Archive::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private Archive $annee;

    #[ManyToMany(targetEntity: Attachment::class, cascade: ["remove"], orphanRemoval: true)]
    #[JoinTable(name: "article_file_attachment")]
    #[JoinColumn(name: "article_id", referencedColumnName: "id")]
    #[InverseJoinColumn(name: "attachments_id", referencedColumnName: "id")]
    private Collection $attachments;

    #[ManyToMany(targetEntity: Attachment::class, cascade: ["remove"], orphanRemoval: true)]
    #[JoinTable(name: "article_image_attachment")]
    #[JoinColumn(name: "article_id", referencedColumnName: "id")]
    #[InverseJoinColumn(name: "attachments_id", referencedColumnName: "id")]
    private ?Collection $images = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateAdd;

    public function __construct()
    {
        $this->dateAdd = new \DateTime();
        $this->attachments = new ArrayCollection();
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
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     * @return $this
     */
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUtitre(): ?string
    {
        return $this->utitre;
    }

    /**
     * @param string $utitre
     * @return $this
     */
    public function setUtitre(string $utitre): self
    {
        $this->utitre = $utitre;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    /**
     * @param string $contenu
     * @return $this
     */
    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * @return type|null
     */
    public function getType(): ?type
    {
        return $this->type;
    }

    /**
     * @param type|null $type
     * @return $this
     */
    public function setType(?type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return archive|null
     */
    public function getAnnee(): ?archive
    {
        return $this->annee;
    }

    /**
     * @param archive|null $annee
     * @return $this
     */
    public function setAnnee(?archive $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    /**
     * @param \DateTimeInterface $dateAdd
     * @return $this
     */
    public function setDateAdd(\DateTimeInterface $dateAdd): self
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    /**
     * @param Attachment $attachment
     * @return $this
     */
    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
        }

        return $this;
    }

    /**
     * @param Attachment $attachment
     * @return $this
     */
    public function removeAttachment(Attachment $attachment): self
    {
        $this->attachments->removeElement($attachment);

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getImages(): ?Collection
    {
        return $this->images;
    }

    /**
     * @param Attachment $attachment
     * @return $this
     */
    public function addImage(Attachment $attachment): self
    {
        if (!$this->images->contains($attachment)) {
            $this->images[] = $attachment;
        }

        return $this;
    }

    /**
     * @param Attachment $attachment
     * @return $this
     */
    public function removeImage(Attachment $attachment): self
    {
        $this->images->removeElement($attachment);

        return $this;
    }
}

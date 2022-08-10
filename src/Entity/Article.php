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
use Doctrine\ORM\Mapping\OneToMany;
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
    #[ORM\JoinColumn(name: "type_code", referencedColumnName: "code", nullable: false)]
    private Type $type;

    #[ORM\ManyToOne(targetEntity: Archive::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private Archive $annee;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateAdd;

    #[ORM\OneToMany(mappedBy: 'articleFiles', targetEntity: Attachment::class)]
    private $files;

    #[ORM\OneToMany(mappedBy: 'articleImages', targetEntity: Attachment::class)]
    private $images;

    #[ORM\OneToMany(mappedBy: 'articleImagesGallery', targetEntity: Attachment::class)]
    private $imagesGallery;


    public function __construct()
    {
        $this->dateAdd = new \DateTime();
        $this->files = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->imagesGallery = new ArrayCollection();
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
     * @return Collection|Attachment[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(Attachment $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setArticleFiles($this);
        }

        return $this;
    }

    public function removeFile(Attachment $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getArticleFiles() === $this) {
                $file->setArticleFiles(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Attachment[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Attachment $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setArticleImages($this);
        }

        return $this;
    }

    public function removeImage(Attachment $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getArticleImages() === $this) {
                $image->setArticleImages(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Attachment[]
     */
    public function getImagesGallery(): Collection
    {
        return $this->imagesGallery;
    }

    public function addImagesGallery(Attachment $imagesGallery): self
    {
        if (!$this->imagesGallery->contains($imagesGallery)) {
            $this->imagesGallery[] = $imagesGallery;
            $imagesGallery->setArticleImagesGallery($this);
        }

        return $this;
    }

    public function removeImagesGallery(Attachment $imagesGallery): self
    {
        if ($this->imagesGallery->removeElement($imagesGallery)) {
            // set the owning side to null (unless already changed)
            if ($imagesGallery->getArticleImagesGallery() === $this) {
                $imagesGallery->setArticleImagesGallery(null);
            }
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment)
    {
        $fileArray = $this->getFiles()->toArray();
        $imageArray = $this->getImages()->toArray();
        $imageGalArray = $this->getImagesGallery()->toArray();
        if(count($fileArray)){
            if(array_search($attachment,$fileArray) || $fileArray[0]->getId() == $attachment->getId()){
                $this->removeFile($attachment);
            }
        }

        if(count($imageArray)){
            if(array_search($attachment,$imageArray) || $imageArray[0]->getId() == $attachment->getId()){
                $this->removeImage($attachment);
            }
        }

        if(count($imageGalArray)){
            if(array_search($attachment,$imageGalArray) || $imageGalArray[0]->getId() == $attachment->getId()){
                $this->removeImagesGallery($attachment);
            }
        }

        return $this;
    }

}

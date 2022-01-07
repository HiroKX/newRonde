<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\Column(type: 'date')]
    private $dateAdd;

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
}

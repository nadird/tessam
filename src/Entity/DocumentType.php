<?php

namespace App\Entity;

use App\Repository\DocumentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentTypeRepository::class)]
class DocumentType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Document::class)]
    private Collection $documents;

    #[ORM\Column]
    private ?bool $isCustomer = null;

    #[ORM\Column]
    private ?bool $isProvider = null;

    #[ORM\Column]
    private ?bool $isProject = null;

    #[ORM\Column]
    private ?bool $isInternal = null;

    #[ORM\Column]
    private ?bool $isAffected = null;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setType($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getType() === $this) {
                $document->setType(null);
            }
        }

        return $this;
    }

    public function isIsCustomer(): ?bool
    {
        return $this->isCustomer;
    }

    public function setIsCustomer(bool $isCustomer): static
    {
        $this->isCustomer = $isCustomer;

        return $this;
    }

    public function isIsProvider(): ?bool
    {
        return $this->isProvider;
    }

    public function setIsProvider(bool $isProvider): static
    {
        $this->isProvider = $isProvider;

        return $this;
    }

    public function isIsProject(): ?bool
    {
        return $this->isProject;
    }

    public function setIsProject(bool $isProject): static
    {
        $this->isProject = $isProject;

        return $this;
    }

    public function isIsInternal(): ?bool
    {
        return $this->isInternal;
    }

    public function setIsInternal(bool $isInternal): static
    {
        $this->isInternal = $isInternal;

        return $this;
    }

    public function isIsAffected(): ?bool
    {
        return $this->isAffected;
    }

    public function setIsAffected(bool $isAffected): static
    {
        $this->isAffected = $isAffected;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\WebJobOfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebJobOfferRepository::class)]
class WebJobOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $publicTitle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $enddate = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\OneToMany(mappedBy: 'jobOffer', targetEntity: WebJobApplication::class)]
    private Collection $webJobApplications;

    public function __construct()
    {
        $this->webJobApplications = new ArrayCollection();
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

    public function getPublicTitle(): ?string
    {
        return $this->publicTitle;
    }

    public function setPublicTitle(string $publicTitle): static
    {
        $this->publicTitle = $publicTitle;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(\DateTimeInterface $enddate): static
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, WebJobApplication>
     */
    public function getWebJobApplications(): Collection
    {
        return $this->webJobApplications;
    }

    public function addWebJobApplication(WebJobApplication $webJobApplication): static
    {
        if (!$this->webJobApplications->contains($webJobApplication)) {
            $this->webJobApplications->add($webJobApplication);
            $webJobApplication->setJobOffer($this);
        }

        return $this;
    }

    public function removeWebJobApplication(WebJobApplication $webJobApplication): static
    {
        if ($this->webJobApplications->removeElement($webJobApplication)) {
            // set the owning side to null (unless already changed)
            if ($webJobApplication->getJobOffer() === $this) {
                $webJobApplication->setJobOffer(null);
            }
        }

        return $this;
    }
}

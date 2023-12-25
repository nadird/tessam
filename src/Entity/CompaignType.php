<?php

namespace App\Entity;

use App\Repository\CompaignTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompaignTypeRepository::class)]
class CompaignType
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Compaign::class)]
    private Collection $compaigns;

    public function __construct()
    {
        $this->compaigns = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, Compaign>
     */
    public function getCompaigns(): Collection
    {
        return $this->compaigns;
    }

    public function addCompaign(Compaign $compaign): static
    {
        if (!$this->compaigns->contains($compaign)) {
            $this->compaigns->add($compaign);
            $compaign->setType($this);
        }

        return $this;
    }

    public function removeCompaign(Compaign $compaign): static
    {
        if ($this->compaigns->removeElement($compaign)) {
            // set the owning side to null (unless already changed)
            if ($compaign->getType() === $this) {
                $compaign->setType(null);
            }
        }

        return $this;
    }
}

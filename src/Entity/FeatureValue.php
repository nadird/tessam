<?php

namespace App\Entity;

use App\Repository\FeatureValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeatureValueRepository::class)]
class FeatureValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'featureValues')]
    private ?FeatureType $type = null;

    

    public function __construct()
    {
    
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getType(): ?FeatureType
    {
        return $this->type;
    }

    public function setType(?FeatureType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function __toString(): string
    {
	    return $this->getValue();
    }
}

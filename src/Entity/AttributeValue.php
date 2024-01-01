<?php

namespace App\Entity;

use App\Repository\AttributeValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttributeValueRepository::class)]
class AttributeValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'attributeValues')]
    private ?AttributeType $type = null;

    #[ORM\ManyToMany(targetEntity: ProductCombination::class, mappedBy: 'attributeValues')]
    private Collection $productCombinations;

    public function __construct()
    {
        $this->productCombinations = new ArrayCollection();
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

    public function getType(): ?AttributeType
    {
        return $this->type;
    }

    public function setType(?AttributeType $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, ProductCombination>
     */
    public function getProductCombinations(): Collection
    {
        return $this->productCombinations;
    }

    public function addProductCombination(ProductCombination $productCombination): static
    {
        if (!$this->productCombinations->contains($productCombination)) {
            $this->productCombinations->add($productCombination);
            $productCombination->addAttributeValue($this);
        }

        return $this;
    }

    public function removeProductCombination(ProductCombination $productCombination): static
    {
        if ($this->productCombinations->removeElement($productCombination)) {
            $productCombination->removeAttributeValue($this);
        }

        return $this;
    }
}

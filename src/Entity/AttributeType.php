<?php

namespace App\Entity;

use App\Repository\AttributeTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttributeTypeRepository::class)]
class AttributeType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: ProductFamily::class, mappedBy: 'attributes')]
    private Collection $productFamilies;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: AttributeValue::class)]
    private Collection $attributeValues;

    public function __construct()
    {
        $this->productFamilies = new ArrayCollection();
        $this->attributeValues = new ArrayCollection();
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

    /**
     * @return Collection<int, ProductFamily>
     */
    public function getProductFamilies(): Collection
    {
        return $this->productFamilies;
    }

    public function addProductFamily(ProductFamily $productFamily): static
    {
        if (!$this->productFamilies->contains($productFamily)) {
            $this->productFamilies->add($productFamily);
            $productFamily->addAttribute($this);
        }

        return $this;
    }

    public function removeProductFamily(ProductFamily $productFamily): static
    {
        if ($this->productFamilies->removeElement($productFamily)) {
            $productFamily->removeAttribute($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, AttributeValue>
     */
    public function getAttributeValues(): Collection
    {
        return $this->attributeValues;
    }

    public function addAttributeValue(AttributeValue $attributeValue): static
    {
        if (!$this->attributeValues->contains($attributeValue)) {
            $this->attributeValues->add($attributeValue);
            $attributeValue->setType($this);
        }

        return $this;
    }

    public function removeAttributeValue(AttributeValue $attributeValue): static
    {
        if ($this->attributeValues->removeElement($attributeValue)) {
            // set the owning side to null (unless already changed)
            if ($attributeValue->getType() === $this) {
                $attributeValue->setType(null);
            }
        }

        return $this;
    }
}

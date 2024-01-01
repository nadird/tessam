<?php

namespace App\Entity;

use App\Repository\ProductFamilyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductFamilyRepository::class)]
class ProductFamily
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\ManyToMany(targetEntity: AttributeType::class, inversedBy: 'productFamilies')]
    private Collection $attributes;

    #[ORM\ManyToMany(targetEntity: FeatureType::class, inversedBy: 'productFamilies')]
    private Collection $features;

    #[ORM\OneToMany(mappedBy: 'family', targetEntity: Product::class)]
    private Collection $products;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->features = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection<int, AttributeType>
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(AttributeType $attribute): static
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes->add($attribute);
        }

        return $this;
    }

    public function removeAttribute(AttributeType $attribute): static
    {
        $this->attributes->removeElement($attribute);

        return $this;
    }

    /**
     * @return Collection<int, FeatureType>
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(FeatureType $feature): static
    {
        if (!$this->features->contains($feature)) {
            $this->features->add($feature);
        }

        return $this;
    }

    public function removeFeature(FeatureType $feature): static
    {
        $this->features->removeElement($feature);

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setFamily($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getFamily() === $this) {
                $product->setFamily(null);
            }
        }

        return $this;
    }
	public function __toString(): string
	{
		return $this->getName();
	}
}

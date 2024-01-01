<?php

namespace App\Entity;

use App\Repository\FeatureTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeatureTypeRepository::class)]
class FeatureType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: ProductFamily::class, mappedBy: 'features')]
    private Collection $productFamilies;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: FeatureValue::class)]
    private Collection $featureValues;

    public function __construct()
    {
        $this->productFamilies = new ArrayCollection();
        $this->featureValues = new ArrayCollection();
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
            $productFamily->addFeature($this);
        }

        return $this;
    }

    public function removeProductFamily(ProductFamily $productFamily): static
    {
        if ($this->productFamilies->removeElement($productFamily)) {
            $productFamily->removeFeature($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, FeatureValue>
     */
    public function getFeatureValues(): Collection
    {
        return $this->featureValues;
    }

    public function addFeatureValue(FeatureValue $featureValue): static
    {
        if (!$this->featureValues->contains($featureValue)) {
            $this->featureValues->add($featureValue);
            $featureValue->setType($this);
        }

        return $this;
    }

    public function removeFeatureValue(FeatureValue $featureValue): static
    {
        if ($this->featureValues->removeElement($featureValue)) {
            // set the owning side to null (unless already changed)
            if ($featureValue->getType() === $this) {
                $featureValue->setType(null);
            }
        }

        return $this;
    }
}

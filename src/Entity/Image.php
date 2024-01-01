<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAdd = null;

    #[ORM\Column(length: 255)]
    private ?string $alt = null;

    #[ORM\OneToMany(mappedBy: 'image', targetEntity: ImageResized::class)]
    private Collection $imageResizeds;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?ImageType $type = null;

    #[ORM\OneToMany(mappedBy: 'principalImage', targetEntity: ImageResized::class)]
    private Collection $resized;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\ManyToMany(targetEntity: ProductCombination::class, mappedBy: 'images')]
    private Collection $productCombinations;

    public function __construct()
    {
        $this->imageResizeds = new ArrayCollection();
        $this->resized = new ArrayCollection();
        $this->productCombinations = new ArrayCollection();
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

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    public function setDateAdd(\DateTimeInterface $dateAdd): static
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): static
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * @return Collection<int, ImageResized>
     */
    public function getImageResizeds(): Collection
    {
        return $this->imageResizeds;
    }

    public function addImageResized(ImageResized $imageResized): static
    {
        if (!$this->imageResizeds->contains($imageResized)) {
            $this->imageResizeds->add($imageResized);
            $imageResized->setImage($this);
        }

        return $this;
    }

    public function removeImageResized(ImageResized $imageResized): static
    {
        if ($this->imageResizeds->removeElement($imageResized)) {
            // set the owning side to null (unless already changed)
            if ($imageResized->getImage() === $this) {
                $imageResized->setImage(null);
            }
        }

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getType(): ?ImageType
    {
        return $this->type;
    }

    public function setType(?ImageType $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, ImageResized>
     */
    public function getResized(): Collection
    {
        return $this->resized;
    }

    public function addResized(ImageResized $resized): static
    {
        if (!$this->resized->contains($resized)) {
            $this->resized->add($resized);
            $resized->setPrincipalImage($this);
        }

        return $this;
    }

    public function removeResized(ImageResized $resized): static
    {
        if ($this->resized->removeElement($resized)) {
            // set the owning side to null (unless already changed)
            if ($resized->getPrincipalImage() === $this) {
                $resized->setPrincipalImage(null);
            }
        }

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

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
            $productCombination->addImage($this);
        }

        return $this;
    }

    public function removeProductCombination(ProductCombination $productCombination): static
    {
        if ($this->productCombinations->removeElement($productCombination)) {
            $productCombination->removeImage($this);
        }

        return $this;
    }
}

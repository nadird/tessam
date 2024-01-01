<?php

namespace App\Entity;

use App\Repository\ImageTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageTypeRepository::class)]
class ImageType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $height = null;

    #[ORM\Column]
    private ?int $width = null;

    #[ORM\Column]
    private ?bool $isProduct = null;

    #[ORM\Column]
    private ?bool $isCategory = null;

    #[ORM\Column]
    private ?bool $isBrand = null;

    #[ORM\Column]
    private ?bool $isBlogCategory = null;

    #[ORM\Column]
    private ?bool $isBlogPost = null;

    #[ORM\Column]
    private ?bool $isEmployee = null;

    #[ORM\Column]
    private ?bool $isSupplier = null;

    #[ORM\Column]
    private ?bool $isShop = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: ImageResized::class)]
    private Collection $imageResizeds;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Image::class)]
    private Collection $images;

    public function __construct()
    {
        $this->imageResizeds = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function isIsProduct(): ?bool
    {
        return $this->isProduct;
    }

    public function setIsProduct(bool $isProduct): static
    {
        $this->isProduct = $isProduct;

        return $this;
    }

    public function isIsCategory(): ?bool
    {
        return $this->isCategory;
    }

    public function setIsCategory(bool $isCategory): static
    {
        $this->isCategory = $isCategory;

        return $this;
    }

    public function isIsBrand(): ?bool
    {
        return $this->isBrand;
    }

    public function setIsBrand(bool $isBrand): static
    {
        $this->isBrand = $isBrand;

        return $this;
    }

    public function isIsBlogCategory(): ?bool
    {
        return $this->isBlogCategory;
    }

    public function setIsBlogCategory(bool $isBlogCategory): static
    {
        $this->isBlogCategory = $isBlogCategory;

        return $this;
    }

    public function isIsBlogPost(): ?bool
    {
        return $this->isBlogPost;
    }

    public function setIsBlogPost(bool $isBlogPost): static
    {
        $this->isBlogPost = $isBlogPost;

        return $this;
    }

    public function isIsEmployee(): ?bool
    {
        return $this->isEmployee;
    }

    public function setIsEmployee(bool $isEmployee): static
    {
        $this->isEmployee = $isEmployee;

        return $this;
    }

    public function isIsSupplier(): ?bool
    {
        return $this->isSupplier;
    }

    public function setIsSupplier(bool $isSupplier): static
    {
        $this->isSupplier = $isSupplier;

        return $this;
    }

    public function isIsShop(): ?bool
    {
        return $this->isShop;
    }

    public function setIsShop(bool $isShop): static
    {
        $this->isShop = $isShop;

        return $this;
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
            $imageResized->setType($this);
        }

        return $this;
    }

    public function removeImageReszied(ImageResized $imageResized): static
    {
        if ($this->imageResizeds->removeElement($imageResized)) {
            // set the owning side to null (unless already changed)
            if ($imageResized->getType() === $this) {
                $imageResized->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setType($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getType() === $this) {
                $image->setType(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShopRepository::class)]
#[ApiResource]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Image $thumb = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'shops')]
    private Collection $products;

    #[ORM\OneToOne(inversedBy: 'shopWarehouse', cascade: ['persist', 'remove'])]
    private ?Warehouse $mainWarehouse = null;

    #[ORM\ManyToMany(targetEntity: Warehouse::class, inversedBy: 'accessibleShops')]
    private Collection $accessibleWarehouse;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->accessibleWarehouse = new ArrayCollection();
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

    public function getThumb(): ?Image
    {
        return $this->thumb;
    }

    public function setThumb(?Image $thumb): static
    {
        $this->thumb = $thumb;

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
            $product->addShop($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeShop($this);
        }

        return $this;
    }

    public function getMainWarehouse(): ?Warehouse
    {
        return $this->mainWarehouse;
    }

    public function setMainWarehouse(?Warehouse $mainWarehouse): static
    {
        $this->mainWarehouse = $mainWarehouse;

        return $this;
    }

    /**
     * @return Collection<int, Warehouse>
     */
    public function getAccessibleWarehouse(): Collection
    {
        return $this->accessibleWarehouse;
    }

    public function addAccessibleWarehouse(Warehouse $accessibleWarehouse): static
    {
        if (!$this->accessibleWarehouse->contains($accessibleWarehouse)) {
            $this->accessibleWarehouse->add($accessibleWarehouse);
        }

        return $this;
    }

    public function removeAccessibleWarehouse(Warehouse $accessibleWarehouse): static
    {
        $this->accessibleWarehouse->removeElement($accessibleWarehouse);

        return $this;
    }
}

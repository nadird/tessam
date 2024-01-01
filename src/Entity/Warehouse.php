<?php

namespace App\Entity;

use App\Repository\WarehouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseRepository::class)]
class Warehouse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'mainWarehouse', cascade: ['persist', 'remove'])]
    private ?Shop $shopWarehouse = null;

    #[ORM\Column]
    private ?bool $isShopWareHouse = null;

    #[ORM\ManyToMany(targetEntity: Shop::class, mappedBy: 'accessibleWarehouse')]
    private Collection $accessibleShops;

    #[ORM\OneToMany(mappedBy: 'warehouse', targetEntity: StockMovement::class)]
    private Collection $stockMovements;

    public function __construct()
    {
        $this->accessibleShops = new ArrayCollection();
        $this->stockMovements = new ArrayCollection();
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

    public function getShopWarehouse(): ?Shop
    {
        return $this->shopWarehouse;
    }

    public function setShopWarehouse(?Shop $shopWarehouse): static
    {
        // unset the owning side of the relation if necessary
        if ($shopWarehouse === null && $this->shopWarehouse !== null) {
            $this->shopWarehouse->setMainWarehouse(null);
        }

        // set the owning side of the relation if necessary
        if ($shopWarehouse !== null && $shopWarehouse->getMainWarehouse() !== $this) {
            $shopWarehouse->setMainWarehouse($this);
        }

        $this->shopWarehouse = $shopWarehouse;

        return $this;
    }

    public function isIsShopWareHouse(): ?bool
    {
        return $this->isShopWareHouse;
    }

    public function setIsShopWareHouse(bool $isShopWareHouse): static
    {
        $this->isShopWareHouse = $isShopWareHouse;

        return $this;
    }

    /**
     * @return Collection<int, Shop>
     */
    public function getAccessibleShops(): Collection
    {
        return $this->accessibleShops;
    }

    public function addAccessibleShop(Shop $accessibleShop): static
    {
        if (!$this->accessibleShops->contains($accessibleShop)) {
            $this->accessibleShops->add($accessibleShop);
            $accessibleShop->addAccessibleWarehouse($this);
        }

        return $this;
    }

    public function removeAccessibleShop(Shop $accessibleShop): static
    {
        if ($this->accessibleShops->removeElement($accessibleShop)) {
            $accessibleShop->removeAccessibleWarehouse($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, StockMovement>
     */
    public function getStockMovements(): Collection
    {
        return $this->stockMovements;
    }

    public function addStockMovement(StockMovement $stockMovement): static
    {
        if (!$this->stockMovements->contains($stockMovement)) {
            $this->stockMovements->add($stockMovement);
            $stockMovement->setWarehouse($this);
        }

        return $this;
    }

    public function removeStockMovement(StockMovement $stockMovement): static
    {
        if ($this->stockMovements->removeElement($stockMovement)) {
            // set the owning side to null (unless already changed)
            if ($stockMovement->getWarehouse() === $this) {
                $stockMovement->setWarehouse(null);
            }
        }

        return $this;
    }
}

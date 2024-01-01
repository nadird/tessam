<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $sku = null;

    #[ORM\Column(type: Types::TEXT,nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT,nullable: true)]
    private ?string $longDescription = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Brand $brand = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'mainProducts')]
    private ?Category $mainCategory = null;

    #[ORM\Column(length: 100,nullable: true)]
    private ?string $barCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seoTitle = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $seoDescription = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'products')]
    private Collection $tags;

    #[ORM\Column]
    private ?bool $isActive = false;

    #[ORM\Column]
    private ?bool $isDeleted = false;

    #[ORM\Column]
    private ?bool $isStandard = true;

    #[ORM\Column]
    private ?bool $isFeatured = false;

    #[ORM\Column]
    private ?bool $isOnSale = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $availableDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAdd = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateUpdate = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $weight = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Supplier $supplier = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'relatedProducts')]
    private ?self $productRelatedTo = null;

    #[ORM\OneToMany(mappedBy: 'productRelatedTo', targetEntity: self::class)]
    private Collection $relatedProducts;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Image::class)]
    private Collection $images;

    #[ORM\ManyToMany(targetEntity: Shop::class, inversedBy: 'products')]
    private Collection $shops;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Tax $tax = null;

    #[ORM\Column(nullable: true)]
    private ?float $supplyPriceHt = null;

    #[ORM\Column(nullable: true)]
    private ?float $supplyPriceTtc = null;

    #[ORM\Column(nullable: true)]
    private ?float $wholeSalePriceHt = null;

    #[ORM\Column(nullable: true)]
    private ?float $wholeSalePriceTtc = null;
	
	#[ORM\Column(nullable: true)]
                                                                   private ?float $priceHt = null;

    #[ORM\Column(nullable: true)]
    private ?float $priceTtc = null;

    #[ORM\Column(nullable: true)]
    private ?int $wholeSaleMoq = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?ProductType $type = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Image $thumb = null;

    #[ORM\ManyToMany(targetEntity: FeatureValue::class, mappedBy: 'product')]
    private Collection $featureValues;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductCombination::class)]
    private Collection $productCombinations;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: StockMovement::class)]
    private Collection $stockMovements;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?ProductFamily $family = null;

    

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
        //$this->productRelatedTo = new ArrayCollection();
        $this->relatedProducts = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->shops = new ArrayCollection();
        $this->featureValues = new ArrayCollection();
        $this->productCombinations = new ArrayCollection();
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

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): static
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getMainCategory(): ?Category
    {
        return $this->mainCategory;
    }

    public function setMainCategory(?Category $mainCategory): static
    {
        $this->mainCategory = $mainCategory;

        return $this;
    }

    public function getBarCode(): ?int
    {
        return $this->barCode;
    }

    public function setBarCode(int $barCode): static
    {
        $this->barCode = $barCode;

        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(?string $seoTitle): static
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(?string $seoDescription): static
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

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

    public function isIsStandard(): ?bool
    {
        return $this->isStandard;
    }

    public function setIsStandard(bool $isStandard): static
    {
        $this->isStandard = $isStandard;

        return $this;
    }

    public function isIsFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): static
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function isIsOnSale(): ?bool
    {
        return $this->isOnSale;
    }

    public function setIsOnSale(bool $isOnSale): static
    {
        $this->isOnSale = $isOnSale;

        return $this;
    }

    public function getAvailableDate(): ?\DateTimeInterface
    {
        return $this->availableDate;
    }

    public function setAvailableDate(\DateTimeInterface $availableDate): static
    {
        $this->availableDate = $availableDate;

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

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(\DateTimeInterface $dateUpdate): static
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getProductRelatedTo(): ?self
    {
        return $this->productRelatedTo;
    }

    public function setProductRelatedTo(?self $productRelatedTo): static
    {
        $this->productRelatedTo = $productRelatedTo;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getRelatedProducts(): Collection
    {
        return $this->relatedProducts;
    }

    public function addRelatedProduct(self $relatedProduct): static
    {
        if (!$this->relatedProducts->contains($relatedProduct)) {
            $this->relatedProducts->add($relatedProduct);
            $relatedProduct->setProductRelatedTo($this);
        }

        return $this;
    }

    public function removeRelatedProduct(self $relatedProduct): static
    {
        if ($this->relatedProducts->removeElement($relatedProduct)) {
            // set the owning side to null (unless already changed)
            if ($relatedProduct->getProductRelatedTo() === $this) {
                $relatedProduct->setProductRelatedTo(null);
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
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Shop>
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    public function addShop(Shop $shop): static
    {
        if (!$this->shops->contains($shop)) {
            $this->shops->add($shop);
        }

        return $this;
    }

    public function removeShop(Shop $shop): static
    {
        $this->shops->removeElement($shop);

        return $this;
    }

    public function getTax(): ?Tax
    {
        return $this->tax;
    }

    public function setTax(?Tax $tax): static
    {
        $this->tax = $tax;

        return $this;
    }

    public function getSupplyPriceHt(): ?float
    {
        return $this->supplyPriceHt;
    }

    public function setSupplyPriceHt(?float $supplyPriceHt): static
    {
        $this->supplyPriceHt = $supplyPriceHt;

        return $this;
    }

    public function getSupplyPriceTtc(): ?float
    {
        return $this->supplyPriceTtc;
    }

    public function setSupplyPriceTtc(?float $supplyPriceTtc): static
    {
        $this->supplyPriceTtc = $supplyPriceTtc;

        return $this;
    }

    public function getWholeSalePriceHt(): ?float
    {
        return $this->wholeSalePriceHt;
    }

    public function setWholeSalePriceHt(?float $wholeSalePriceHt): static
    {
        $this->wholeSalePriceHt = $wholeSalePriceHt;

        return $this;
    }

    public function getWholeSalePriceTtc(): ?float
    {
        return $this->wholeSalePriceTtc;
    }

    public function setWholeSalePriceTtc(?float $wholeSalePriceTtc): static
    {
        $this->wholeSalePriceTtc = $wholeSalePriceTtc;

        return $this;
    }

    public function getPriceHt(): ?float
    {
        return $this->priceHt;
    }

    public function setPriceHt(float $priceHt): static
    {
        $this->priceHt = $priceHt;

        return $this;
    }

    public function getPriceTtc(): ?float
    {
        return $this->priceTtc;
    }

    public function setPriceTtc(?float $priceTtc): static
    {
        $this->priceTtc = $priceTtc;

        return $this;
    }

    public function getWholeSaleMoq(): ?int
    {
        return $this->wholeSaleMoq;
    }

    public function setWholeSaleMoq(?int $wholeSaleMoq): static
    {
        $this->wholeSaleMoq = $wholeSaleMoq;

        return $this;
    }

    public function getType(): ?ProductType
    {
        return $this->type;
    }

    public function setType(?ProductType $type): static
    {
        $this->type = $type;

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
            $featureValue->addProduct($this);
        }

        return $this;
    }

    public function removeFeatureValue(FeatureValue $featureValue): static
    {
        if ($this->featureValues->removeElement($featureValue)) {
            $featureValue->removeProduct($this);
        }

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
            $productCombination->setProduct($this);
        }

        return $this;
    }

    public function removeProductCombination(ProductCombination $productCombination): static
    {
        if ($this->productCombinations->removeElement($productCombination)) {
            // set the owning side to null (unless already changed)
            if ($productCombination->getProduct() === $this) {
                $productCombination->setProduct(null);
            }
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
            $stockMovement->setProduct($this);
        }

        return $this;
    }

    public function removeStockMovement(StockMovement $stockMovement): static
    {
        if ($this->stockMovements->removeElement($stockMovement)) {
            // set the owning side to null (unless already changed)
            if ($stockMovement->getProduct() === $this) {
                $stockMovement->setProduct(null);
            }
        }

        return $this;
    }

    public function getFamily(): ?ProductFamily
    {
        return $this->family;
    }

    public function setFamily(?ProductFamily $family): static
    {
        $this->family = $family;

        return $this;
    }
}

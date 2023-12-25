<?php

namespace App\Entity;

use App\Repository\BlogCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogCategoryRepository::class)]
class BlogCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'parentBlogCategories')]
    private Collection $parentCategories;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'parentCategories')]
    private Collection $parentBlogCategories;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToMany(targetEntity: BlogPost::class, mappedBy: 'categories')]
    private Collection $blogPosts;

    public function __construct()
    {
        $this->parentCategories = new ArrayCollection();
        $this->parentBlogCategories = new ArrayCollection();
        $this->blogPosts = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParentCategories(): Collection
    {
        return $this->parentCategories;
    }

    public function addParentCategory(self $parentCategory): static
    {
        if (!$this->parentCategories->contains($parentCategory)) {
            $this->parentCategories->add($parentCategory);
        }

        return $this;
    }

    public function removeParentCategory(self $parentCategory): static
    {
        $this->parentCategories->removeElement($parentCategory);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParentBlogCategories(): Collection
    {
        return $this->parentBlogCategories;
    }

    public function addParentBlogCategory(self $parentBlogCategory): static
    {
        if (!$this->parentBlogCategories->contains($parentBlogCategory)) {
            $this->parentBlogCategories->add($parentBlogCategory);
            $parentBlogCategory->addParentCategory($this);
        }

        return $this;
    }

    public function removeParentBlogCategory(self $parentBlogCategory): static
    {
        if ($this->parentBlogCategories->removeElement($parentBlogCategory)) {
            $parentBlogCategory->removeParentCategory($this);
        }

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public function getBlogPosts(): Collection
    {
        return $this->blogPosts;
    }

    public function addBlogPost(BlogPost $blogPost): static
    {
        if (!$this->blogPosts->contains($blogPost)) {
            $this->blogPosts->add($blogPost);
            $blogPost->addCategory($this);
        }

        return $this;
    }

    public function removeBlogPost(BlogPost $blogPost): static
    {
        if ($this->blogPosts->removeElement($blogPost)) {
            $blogPost->removeCategory($this);
        }

        return $this;
    }
	public function __toString(): string
	{
		return $this->getName();
	}
}

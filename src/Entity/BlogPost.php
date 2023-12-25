<?php

namespace App\Entity;

use App\Repository\BlogPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogPostRepository::class)]
class BlogPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $publicTitle = null;

    #[ORM\Column(length: 255)]
    private ?string $seoTitle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 350, nullable: true)]
    private ?string $seoDescription = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgOg = null;

    #[ORM\ManyToOne(inversedBy: 'blogPosts')]
    private ?Employee $author = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastModified = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'relatedBlogPosts')]
    private Collection $relatedPosts;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'relatedPosts')]
    private Collection $relatedBlogPosts;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: BlogCategory::class, inversedBy: 'blogPosts')]
    private Collection $categories;

    #[ORM\Column]
    private ?bool $isFeatured = null;

    public function __construct()
    {
        $this->relatedPosts = new ArrayCollection();
        $this->relatedBlogPosts = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublicTitle(): ?string
    {
        return $this->publicTitle;
    }

    public function setPublicTitle(string $publicTitle): static
    {
        $this->publicTitle = $publicTitle;

        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(string $seoTitle): static
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

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

    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(?string $seoDescription): static
    {
        $this->seoDescription = $seoDescription;

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

    public function getImgOg(): ?string
    {
        return $this->imgOg;
    }

    public function setImgOg(?string $imgOg): static
    {
        $this->imgOg = $imgOg;

        return $this;
    }

    public function getAuthor(): ?Employee
    {
        return $this->author;
    }

    public function setAuthor(?Employee $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getLastModified(): ?\DateTimeInterface
    {
        return $this->lastModified;
    }

    public function setLastModified(\DateTimeInterface $lastModified): static
    {
        $this->lastModified = $lastModified;

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

    /**
     * @return Collection<int, self>
     */
    public function getRelatedPosts(): Collection
    {
        return $this->relatedPosts;
    }

    public function addRelatedPost(self $relatedPost): static
    {
        if (!$this->relatedPosts->contains($relatedPost)) {
            $this->relatedPosts->add($relatedPost);
        }

        return $this;
    }

    public function removeRelatedPost(self $relatedPost): static
    {
        $this->relatedPosts->removeElement($relatedPost);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getRelatedBlogPosts(): Collection
    {
        return $this->relatedBlogPosts;
    }

    public function addRelatedBlogPost(self $relatedBlogPost): static
    {
        if (!$this->relatedBlogPosts->contains($relatedBlogPost)) {
            $this->relatedBlogPosts->add($relatedBlogPost);
            $relatedBlogPost->addRelatedPost($this);
        }

        return $this;
    }

    public function removeRelatedBlogPost(self $relatedBlogPost): static
    {
        if ($this->relatedBlogPosts->removeElement($relatedBlogPost)) {
            $relatedBlogPost->removeRelatedPost($this);
        }

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
     * @return Collection<int, BlogCategory>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(BlogCategory $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(BlogCategory $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
	public function __toString(): string
         	{
         		return $this->publicTitle;
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
}

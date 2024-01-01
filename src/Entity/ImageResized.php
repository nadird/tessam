<?php

namespace App\Entity;

use App\Repository\ImageResizedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageResizedRepository::class)]
class ImageResized
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'imageResizeds')]
    private ?Image $image = null;

    #[ORM\ManyToOne(inversedBy: 'imageResizeds')]
    private ?ImageType $type = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }
public function __toString()
	{
		return $this->url;
	}
}

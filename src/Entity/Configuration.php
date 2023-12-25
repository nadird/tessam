<?php

namespace App\Entity;

use App\Repository\ConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigurationRepository::class)]
class Configuration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $configkey = null;

    #[ORM\Column(length: 255)]
    private ?string $configvalue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfigkey(): ?string
    {
        return $this->configkey;
    }

    public function setConfigkey(string $configkey): static
    {
        $this->configkey = $configkey;

        return $this;
    }

    public function getConfigvalue(): ?string
    {
        return $this->configvalue;
    }

    public function setConfigvalue(string $configvalue): static
    {
        $this->configvalue = $configvalue;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\VisitorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitorRepository::class)]
class Visitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $evssid = null;

    #[ORM\OneToMany(mappedBy: 'visitor', targetEntity: TraficTracking::class)]
    private Collection $traficTracking;

    #[ORM\ManyToOne(inversedBy: 'visitors')]
    private ?Compaign $compaign = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAdd = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    public function __construct()
    {
        $this->traficTracking = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvssid(): ?string
    {
        return $this->evssid;
    }

    public function setEvssid(string $evssid): static
    {
        $this->evssid = $evssid;

        return $this;
    }

    /**
     * @return Collection<int, TraficTracking>
     */
    public function getTraficTracking(): Collection
    {
        return $this->traficTracking;
    }

    public function addTraficTracking(TraficTracking $traficTracking): static
    {
        if (!$this->traficTracking->contains($traficTracking)) {
            $this->traficTracking->add($traficTracking);
            $traficTracking->setVisitor($this);
        }

        return $this;
    }

    public function removeTraficTracking(TraficTracking $traficTracking): static
    {
        if ($this->traficTracking->removeElement($traficTracking)) {
            // set the owning side to null (unless already changed)
            if ($traficTracking->getVisitor() === $this) {
                $traficTracking->setVisitor(null);
            }
        }

        return $this;
    }

    public function getCompaign(): ?Compaign
    {
        return $this->compaign;
    }

    public function setCompaign(?Compaign $compaign): static
    {
        $this->compaign = $compaign;

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

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}

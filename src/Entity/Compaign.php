<?php

namespace App\Entity;

use App\Repository\CompaignRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompaignRepository::class)]
class Compaign
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'compaigns')]
    private ?CompaignType $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $video = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAdd = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $finalUrl = null;

    #[ORM\ManyToOne(inversedBy: 'compaigns')]
    private ?Employee $employee = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $qr = null;


    #[ORM\OneToMany(mappedBy: 'utm_campaign', targetEntity: TraficTracking::class)]
    private Collection $traficTrackings;

    #[ORM\OneToMany(mappedBy: 'compaign', targetEntity: Visitor::class)]
    private Collection $visitors;

    public function __construct()
    {

        $this->traficTrackings = new ArrayCollection();
        $this->visitors = new ArrayCollection();
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

    public function getType(): ?CompaignType
    {
        return $this->type;
    }

    public function setType(?CompaignType $type): static
    {
        $this->type = $type;

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

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): static
    {
        $this->video = $video;

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

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    public function setDateAdd(\DateTimeInterface $dateAdd): static
    {
        $this->dateAdd = $dateAdd;

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

    public function getFinalUrl(): ?string
    {
        return $this->finalUrl;
    }

    public function setFinalUrl(?string $finalUrl): static
    {
        $this->finalUrl = $finalUrl;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getQr(): ?string
    {
        return $this->qr;
    }

    public function setQr(?string $qr): static
    {
        $this->qr = $qr;

        return $this;
    }
	
    /**
     * @return Collection<int, TraficTracking>
     */
    public function getTraficTrackings(): Collection
    {
        return $this->traficTrackings;
    }

    public function addTraficTracking(TraficTracking $traficTracking): static
    {
        if (!$this->traficTrackings->contains($traficTracking)) {
            $this->traficTrackings->add($traficTracking);
            $traficTracking->setUtmCampaign($this);
        }

        return $this;
    }

    public function removeTraficTracking(TraficTracking $traficTracking): static
    {
        if ($this->traficTrackings->removeElement($traficTracking)) {
            // set the owning side to null (unless already changed)
            if ($traficTracking->getUtmCampaign() === $this) {
                $traficTracking->setUtmCampaign(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Visitor>
     */
    public function getVisitors(): Collection
    {
        return $this->visitors;
    }

    public function addVisitor(Visitor $visitor): static
    {
        if (!$this->visitors->contains($visitor)) {
            $this->visitors->add($visitor);
            $visitor->setCompaign($this);
        }

        return $this;
    }

    public function removeVisitor(Visitor $visitor): static
    {
        if ($this->visitors->removeElement($visitor)) {
            // set the owning side to null (unless already changed)
            if ($visitor->getCompaign() === $this) {
                $visitor->setCompaign(null);
            }
        }

        return $this;
    }
}

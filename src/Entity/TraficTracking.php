<?php

namespace App\Entity;

use App\Repository\TraficTrackingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraficTrackingRepository::class)]
class TraficTracking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    private ?string $userAgent = null;

    #[ORM\Column(length: 255)]
    private ?string $ip = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $referer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $acceptLanguage = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $uri = null;

    #[ORM\Column( nullable: true)]
    private ?array $cookies = null;

    #[ORM\Column(nullable: true)]
    private ?array $params = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAdd = null;

    #[ORM\ManyToOne(inversedBy: 'traficTrackings')]
    private ?Compaign $utm_campaign = null;

    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phpsessid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $evuuid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $evssid = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\ManyToOne(inversedBy: 'traficTracking')]
    private ?Visitor $visitor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function getReferer(): ?string
    {
        return $this->referer;
    }

    public function setReferer(?string $referer): static
    {
        $this->referer = $referer;

        return $this;
    }

    public function getAcceptLanguage(): ?string
    {
        return $this->acceptLanguage;
    }

    public function setAcceptLanguage(?string $acceptLanguage): static
    {
        $this->acceptLanguage = $acceptLanguage;

        return $this;
    }
	

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(?string $uri): static
    {
        $this->uri = $uri;

        return $this;
    }

    public function getCookies(): ?array
    {
        return $this->cookies;
    }

    public function setCookies(?array $cookies): static
    {
        $this->cookies = $cookies;

        return $this;
    }

    public function getParams(): ?array
    {
        return $this->params;
    }

    public function setParams(?array $params): static
    {
        $this->params = $params;

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

    public function getUtmCampaign(): ?Compaign
    {
        return $this->utm_campaign;
    }

    public function setUtmCampaign(?Compaign $utm_campaign): static
    {
        $this->utm_campaign = $utm_campaign;

        return $this;
    }
	

    public function getPhpsessid(): ?string
    {
        return $this->phpsessid;
    }

    public function setPhpsessid(?string $phpsessid): static
    {
        $this->phpsessid = $phpsessid;

        return $this;
    }

    public function getEvuuid(): ?string
    {
        return $this->evuuid;
    }

    public function setEvuuid(?string $evuuid): static
    {
        $this->evuuid = $evuuid;

        return $this;
    }

    public function getEvssid(): ?string
    {
        return $this->evssid;
    }

    public function setEvssid(?string $evssid): static
    {
        $this->evssid = $evssid;

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

    public function getVisitor(): ?Visitor
    {
        return $this->visitor;
    }

    public function setVisitor(?Visitor $visitor): static
    {
        $this->visitor = $visitor;

        return $this;
    }
}

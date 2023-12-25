<?php

namespace App\Entity;

use App\Repository\TheCompanyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TheCompanyRepository::class)]
class TheCompany
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $longDescription = null;

    #[ORM\Column(length: 255)]
    private ?string $logoMainLight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoMainDark = null;

    #[ORM\Column(length: 255)]
    private ?string $logoMobileLight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoMobileDark = null;

    #[ORM\Column(length: 255)]
    private ?string $favicon = null;

    #[ORM\Column(length: 255)]
    private ?string $ogImage = null;

    #[ORM\Column(length: 255)]
    private ?string $imageHome = null;

    #[ORM\Column(length: 255)]
    private ?string $videoHome = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $secondText = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $secondImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebookLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagram = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $twitter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tiktok = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $messenger = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $whatsapp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $viber = null;

    #[ORM\Column(length: 255)]
    private ?string $email1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email2 = null;

    #[ORM\Column(length: 255)]
    private ?string $phone1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $indexTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $indexSubtitle = null;

    #[ORM\Column(length: 255)]
    private ?string $raisonSocial = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 500)]
    private ?string $localisation = null;

    #[ORM\Column]
    private ?bool $diplayPrices = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebookPixel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $analytics = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleTagManager = null;

    #[ORM\Column(length: 255)]
    private ?string $logoPrint = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $agrement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $agrementDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ntnpi = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private  ?\DateTimeInterface $ntnpiDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nfgcmpi = null;

    #[ORM\ManyToOne(inversedBy: 'theCompanies')]
    private ?Employee $legalRep = null;

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

    public function getLogoMainLight(): ?string
    {
        return $this->logoMainLight;
    }

    public function setLogoMainLight(string $logoMainLight): static
    {
        $this->logoMainLight = $logoMainLight;

        return $this;
    }

    public function getLogoMainDark(): ?string
    {
        return $this->logoMainDark;
    }

    public function setLogoMainDark(?string $logoMainDark): static
    {
        $this->logoMainDark = $logoMainDark;

        return $this;
    }

    public function getLogoMobileLight(): ?string
    {
        return $this->logoMobileLight;
    }

    public function setLogoMobileLight(string $logoMobileLight): static
    {
        $this->logoMobileLight = $logoMobileLight;

        return $this;
    }

    public function getLogoMobileDark(): ?string
    {
        return $this->logoMobileDark;
    }

    public function setLogoMobileDark(?string $logoMobileDark): static
    {
        $this->logoMobileDark = $logoMobileDark;

        return $this;
    }

    public function getFavicon(): ?string
    {
        return $this->favicon;
    }

    public function setFavicon(string $favicon): static
    {
        $this->favicon = $favicon;

        return $this;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    public function setOgImage(string $ogImage): static
    {
        $this->ogImage = $ogImage;

        return $this;
    }

    public function getImageHome(): ?string
    {
        return $this->imageHome;
    }

    public function setImageHome(string $imageHome): static
    {
        $this->imageHome = $imageHome;

        return $this;
    }

    public function getVideoHome(): ?string
    {
        return $this->videoHome;
    }

    public function setVideoHome(string $videoHome): static
    {
        $this->videoHome = $videoHome;

        return $this;
    }

    public function getSecondText(): ?string
    {
        return $this->secondText;
    }

    public function setSecondText(?string $secondText): static
    {
        $this->secondText = $secondText;

        return $this;
    }

    public function getSecondImage(): ?string
    {
        return $this->secondImage;
    }

    public function setSecondImage(?string $secondImage): static
    {
        $this->secondImage = $secondImage;

        return $this;
    }

    public function getFacebookLink(): ?string
    {
        return $this->facebookLink;
    }

    public function setFacebookLink(?string $facebookLink): static
    {
        $this->facebookLink = $facebookLink;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): static
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): static
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getTiktok(): ?string
    {
        return $this->tiktok;
    }

    public function setTiktok(?string $tiktok): static
    {
        $this->tiktok = $tiktok;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getMessenger(): ?string
    {
        return $this->messenger;
    }

    public function setMessenger(?string $messenger): static
    {
        $this->messenger = $messenger;

        return $this;
    }

    public function getWhatsapp(): ?string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(?string $whatsapp): static
    {
        $this->whatsapp = $whatsapp;

        return $this;
    }

    public function getViber(): ?string
    {
        return $this->viber;
    }

    public function setViber(?string $viber): static
    {
        $this->viber = $viber;

        return $this;
    }

    public function getEmail1(): ?string
    {
        return $this->email1;
    }

    public function setEmail1(string $email1): static
    {
        $this->email1 = $email1;

        return $this;
    }

    public function getEmail2(): ?string
    {
        return $this->email2;
    }

    public function setEmail2(?string $email2): static
    {
        $this->email2 = $email2;

        return $this;
    }

    public function getPhone1(): ?string
    {
        return $this->phone1;
    }

    public function setPhone1(string $phone1): static
    {
        $this->phone1 = $phone1;

        return $this;
    }

    public function getPhone2(): ?string
    {
        return $this->phone2;
    }

    public function setPhone2(?string $phone2): static
    {
        $this->phone2 = $phone2;

        return $this;
    }

    public function getIndexTitle(): ?string
    {
        return $this->indexTitle;
    }

    public function setIndexTitle(?string $indexTitle): static
    {
        $this->indexTitle = $indexTitle;

        return $this;
    }

    public function getIndexSubtitle(): ?string
    {
        return $this->indexSubtitle;
    }

    public function setIndexSubtitle(?string $indexSubtitle): static
    {
        $this->indexSubtitle = $indexSubtitle;

        return $this;
    }

    public function getRaisonSocial(): ?string
    {
        return $this->raisonSocial;
    }

    public function setRaisonSocial(string $raisonSocial): static
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function isDiplayPrices(): ?bool
    {
        return $this->diplayPrices;
    }

    public function setDiplayPrices(bool $diplayPrices): static
    {
        $this->diplayPrices = $diplayPrices;

        return $this;
    }

    public function getFacebookPixel(): ?string
    {
        return $this->facebookPixel;
    }

    public function setFacebookPixel(?string $facebookPixel): static
    {
        $this->facebookPixel = $facebookPixel;

        return $this;
    }

    public function getAnalytics(): ?string
    {
        return $this->analytics;
    }

    public function setAnalytics(?string $analytics): static
    {
        $this->analytics = $analytics;

        return $this;
    }

    public function getGoogleTagManager(): ?string
    {
        return $this->googleTagManager;
    }

    public function setGoogleTagManager(?string $googleTagManager): static
    {
        $this->googleTagManager = $googleTagManager;

        return $this;
    }

    public function getLogoPrint(): ?string
    {
        return $this->logoPrint;
    }

    public function setLogoPrint(string $logoPrint): static
    {
        $this->logoPrint = $logoPrint;

        return $this;
    }

    public function getAgrement(): ?string
    {
        return $this->agrement;
    }

    public function setAgrement(?string $agrement): static
    {
        $this->agrement = $agrement;

        return $this;
    }

    public function getAgrementDate(): ?\DateTimeInterface
    {
        return $this->agrementDate;
    }

    public function setAgrementDate(?\DateTimeInterface $agrementDate): static
    {
        $this->agrementDate = $agrementDate;

        return $this;
    }

    public function getNtnpi(): ?string
    {
        return $this->ntnpi;
    }

    public function setNtnpi(?string $ntnpi): static
    {
        $this->ntnpi = $ntnpi;

        return $this;
    }

    public function getNtnpiDate(): ?\DateTimeInterface
    {
        return $this->ntnpiDate;
    }

    public function setNtnpiDate(?\DateTimeInterface $ntnpiDate): static
    {
        $this->ntnpiDate = $ntnpiDate;

        return $this;
    }

    public function getNfgcmpi(): ?string
    {
        return $this->nfgcmpi;
    }

    public function setNfgcmpi(?string $nfgcmpi): static
    {
        $this->nfgcmpi = $nfgcmpi;

        return $this;
    }

    public function getLegalRep(): ?Employee
    {
        return $this->legalRep;
    }

    public function setLegalRep(?Employee $legalRep): static
    {
        $this->legalRep = $legalRep;

        return $this;
    }
}

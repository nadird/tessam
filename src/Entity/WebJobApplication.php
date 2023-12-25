<?php

namespace App\Entity;

use App\Repository\WebJobApplicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WebJobApplicationRepository::class)]
class WebJobApplication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'app.form.webjobapplication.firstname.notblank')]
    #[Assert\Length(min: 2, max: 100, minMessage: 'app.form.webjobapplication.firstname.min', maxMessage: 'app.form.webjobapplication.firstname.max')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]+$/i', message: 'app.form.webjobapplication.firstname.regex')]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'app.form.webjobapplication.lastname.notblank')]
    #[Assert\Length(min: 2, max: 100, minMessage: 'app.form.webjobapplication.lastname.min', maxMessage: 'app.form.webjobapplication.lastname.max')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]+$/i', message: 'app.form.webjobapplication.lastname.regex')]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'app.form.webjobapplication.phone.notblank')]
    #[Assert\Length(min: 2, max: 100, minMessage: 'app.form.webjobapplication.phone.min', maxMessage: 'app.form.webjobapplication.phone.max')]
    #[Assert\Regex(pattern: '/^\+?[0-9]{1,3}?[-. ]?(\([0-9]{1,3}\)|[0-9]{1,3})[-. ]?[0-9]{1,4}[-. ]?[0-9]{1,4}[-. ]?[0-9]{1,4}$/', message: 'app.form.webjobapplication.phone.regex')]
    
    private ?string $phone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'app.form.webjobapplication.dob.notblank')]
    #[Assert\LessThanOrEqual(value: '-18 years', message: 'app.form.webjobapplication.dob.lessthanorequal')]
    #[Assert\GreaterThanOrEqual(value: '-65 years', message: 'app.form.webjobapplication.dob.greaterthanorequal')]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'app.form.webjobapplication.address.notblank')]
    #[Assert\Length(min: 2, max: 200, minMessage: 'app.form.webjobapplication.address.min', maxMessage: 'app.form.webjobapplication.address.max')]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Assert\File(mimeTypes: ['application/pdf', 'application/x-pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword'], mimeTypesMessage: 'app.form.webjobapplication.resume.file')]
    private ?string $resume = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message: 'app.form.webjobapplication.linkedin.url')]
    #[Assert\Length(max: 255, maxMessage: 'app.form.webjobapplication.linkedin.max')]
    private ?string $linkedin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 5000, maxMessage: 'app.form.webjobapplication.additionalinfos.max')]
    private ?string $additionalInfos = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'webJobApplications')]
    private ?WebJobOffer $jobOffer = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'app.form.webjobapplication.email.notblank')]
    #[Assert\Email(message: 'app.form.webjobapplication.email.email')]
    #[Assert\Length(max: 255, maxMessage: 'app.form.webjobapplication.email.max')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/',message: 'app.form.webjobapplication.email.regex')]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ip = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(\DateTimeInterface $dob): static
    {
        $this->dob = $dob;

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

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): static
    {
        $this->resume = $resume;

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

    public function getAdditionalInfos(): ?string
    {
        return $this->additionalInfos;
    }

    public function setAdditionalInfos(?string $additionalInfos): static
    {
        $this->additionalInfos = $additionalInfos;

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

    public function getJobOffer(): ?WebJobOffer
    {
        return $this->jobOffer;
    }

    public function setJobOffer(?WebJobOffer $jobOffer): static
    {
        $this->jobOffer = $jobOffer;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }
}

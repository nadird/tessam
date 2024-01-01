<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CustomerRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[UniqueEntity(fields: ['phone'], message: 'There is already an account with this phone')]
#[ApiResource(
	description: 'All Customers B2C of the company',
	operations: [
		new get(),
		new getCollection(),
		new post(),
		new put(),
	],
	normalizationContext: [
		'groups' => ['customer:read'],
	],
	denormalizationContext: [
		'groups' => ['customer:write'],
	],
)]
class Customer implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['customer:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['customer:read', 'customer:write'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['customer:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    #[ApiFilter(BooleanFilter::class)]
    private $isVerified = false;

    #[ORM\Column(length: 255)]
    #[Groups(['customer:read', 'customer:write'])]
    #[ApiFilter(SearchFilter::class,strategy: 'partial')]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['customer:read', 'customer:write'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['customer:read', 'customer:write'])]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column]
    #[Groups(['customer:read', 'customer:write'])]
    #[ApiFilter(BooleanFilter::class)]
    private ?bool $isActive = false;
    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[Groups(['customer:read', 'customer:write'])]
    private ?CustomerState $state = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['customer:read', 'customer:write'])]
    private ?\DateTimeInterface $dateAdd = null;
	
    #[ORM\Column]
    #[Groups(['customer:read', 'customer:write'])]
    #[ApiFilter(BooleanFilter::class)]
    private ?bool $isDeleted = false;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    private ?Employee $employee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['customer:read', 'customer:write'])]
    private ?\DateTimeInterface $dateUpdated = null;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Document::class)]
    private Collection $documents;
	

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dob = null;
	

    #[ORM\Column(type: 'uuid')]
    #[Groups(['customer:read', 'customer:write'])]
    private ?Uuid $uuid = null;

    public function __construct()
    {
        
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
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

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(?string $Address): static
    {
        $this->Address = $Address;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

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
	

    public function getState(): ?CustomerState
    {
        return $this->state;
    }

    public function setState(?CustomerState $state): static
    {
        $this->state = $state;

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

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->dateUpdated;
    }
	public function getDateUpdatedAgo(): string
	{
		return Carbon::instance($this->dateUpdated)->diffForHumans();
	}
    public function setDateUpdated(\DateTimeInterface $dateUpdated): static
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setCustomer($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getCustomer() === $this) {
                $document->setCustomer(null);
            }
        }

        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(?\DateTimeInterface $dob): static
    {
        $this->dob = $dob;

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }
	public function __toString(): string
	{
		return $this->getFirstname().' '.$this->getLastname();
	}
	public function getFullname():string
	{
		return $this->getFirstname().' '.$this->getLastname();
	}
	public function __serialize(): array
	{
		return [
			'id' => $this->id,
			'email' => $this->email,
			'roles' => $this->roles,
			'isVerified' => $this->isVerified,
			'firstname' => $this->firstname,
			'lastname' => $this->lastname,
			'phone' => $this->phone,
			'Address' => $this->Address,
			'picture' => $this->picture,
			'isActive' => $this->isActive,
			'state' => $this->state->getId(),
			'dateAdd' => $this->dateAdd,
			'isDeleted' => $this->isDeleted,
			'employee' => $this->employee->getId(),
			'dateUpdated' => $this->dateUpdated,
			'dob' => $this->dob,
			'uuid' => $this->uuid,
		];
	}
	
}

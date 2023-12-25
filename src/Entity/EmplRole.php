<?php

namespace App\Entity;

use App\Repository\EmplRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmplRoleRepository::class)]
class EmplRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\ManyToMany(targetEntity: EmplJobs::class, mappedBy: 'roles')]
    private Collection $emplJobs;

    public function __construct()
    {
        $this->emplJobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, EmplJobs>
     */
    public function getEmplJobs(): Collection
    {
        return $this->emplJobs;
    }

    public function addEmplJob(EmplJobs $emplJob): static
    {
        if (!$this->emplJobs->contains($emplJob)) {
            $this->emplJobs->add($emplJob);
            $emplJob->addRole($this);
        }

        return $this;
    }

    public function removeEmplJob(EmplJobs $emplJob): static
    {
        if ($this->emplJobs->removeElement($emplJob)) {
            $emplJob->removeRole($this);
        }

        return $this;
    }
	public function __toString(): string
	{
		return $this->getRole();
	}
}

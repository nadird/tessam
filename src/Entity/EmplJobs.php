<?php

namespace App\Entity;

use App\Repository\EmplJobsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmplJobsRepository::class)]
class EmplJobs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'job', targetEntity: Employee::class)]
    private Collection $employees;

    #[ORM\ManyToMany(targetEntity: EmplRole::class, inversedBy: 'emplJobs')]
    private Collection $roles;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->roles = new ArrayCollection();
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

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): static
    {
        if (!$this->employees->contains($employee)) {
            $this->employees->add($employee);
            $employee->setJob($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): static
    {
        if ($this->employees->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getJob() === $this) {
                $employee->setJob(null);
            }
        }

        return $this;
    }
	public function __toString(): string
               	{
               		return $this->getName();
               	}

    /**
     * @return Collection<int, EmplRole>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(EmplRole $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function removeRole(EmplRole $role): static
    {
        $this->roles->removeElement($role);

        return $this;
    }
}

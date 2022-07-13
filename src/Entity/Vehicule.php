<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20)]
    private $immatriculation;

    #[ORM\OneToMany(mappedBy: 'vehicule', targetEntity: Kilometrage::class, orphanRemoval: true)]
    private $kilometrages;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'Vehicules')]
    private $users;

    public function __construct()
    {
        $this->kilometrages = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    /**
     * @return Collection<int, Kilometrage>
     */
    public function getKilometrages(): Collection
    {
        return $this->kilometrages;
    }

    public function addKilometrage(Kilometrage $kilometrage): self
    {
        if (!$this->kilometrages->contains($kilometrage)) {
            $this->kilometrages[] = $kilometrage;
            $kilometrage->setVehicule($this);
        }

        return $this;
    }

    public function removeKilometrage(Kilometrage $kilometrage): self
    {
        if ($this->kilometrages->removeElement($kilometrage)) {
            // set the owning side to null (unless already changed)
            if ($kilometrage->getVehicule() === $this) {
                $kilometrage->setVehicule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addVehicule($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeVehicule($this);
        }

        return $this;
    }
}

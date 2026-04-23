<?php

namespace App\Entity;

use App\Repository\AllergenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllergenRepository::class)]
class Allergen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $item = null;

    /**
     * @var Collection<int, Plate>
     */
    #[ORM\ManyToMany(targetEntity: Plate::class, mappedBy: 'allergens')]
    private Collection $plates;

    public function __construct()
    {
        $this->plates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(string $item): static
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return Collection<int, Plate>
     */
    public function getPlates(): Collection
    {
        return $this->plates;
    }

    public function addPlate(Plate $plate): static
    {
        if (!$this->plates->contains($plate)) {
            $this->plates->add($plate);
            $plate->addAllergen($this);
        }

        return $this;
    }

    public function removePlate(Plate $plate): static
    {
        if ($this->plates->removeElement($plate)) {
            $plate->removeAllergen($this);
        }

        return $this;
    }
}

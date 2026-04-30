<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $relatedOrder = null;

    /**
     * @var Collection<int, Plate>
     */
    #[ORM\ManyToMany(targetEntity: Plate::class, inversedBy: 'orderItems')]
    private Collection $plate;

    public function __construct()
    {
        $this->plate = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelatedOrder(): ?Order
    {
        return $this->relatedOrder;
    }

    public function setRelatedOrder(?Order $relatedOrder): static
    {
        $this->relatedOrder = $relatedOrder;

        return $this;
    }

    /**
     * @return Collection<int, Plate>
     */
    public function getPlate(): Collection
    {
        return $this->plate;
    }

    public function addPlate(Plate $plate): static
    {
        if (!$this->plate->contains($plate)) {
            $this->plate->add($plate);
        }

        return $this;
    }

    public function removePlate(Plate $plate): static
    {
        $this->plate->removeElement($plate);

        return $this;
    }
}

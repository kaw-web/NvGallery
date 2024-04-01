<?php

namespace App\Entity;

use App\Repository\InboundsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InboundsRepository::class)]
class Inbounds
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'inbounds')]
    #[ORM\JoinColumn(name:'inventory_id', referencedColumnName: 'id')]
    private ?Inventories $Inventory = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $arrivalDate = null;

    #[ORM\Column]
    private ?float $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInventory(): ?Inventories
    {
        return $this->Inventory;
    }

    public function setInventory(?Inventories $Inventory): static
    {
        $this->Inventory = $Inventory;

        return $this;
    }

    public function getArrivalDate(): ?\DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(?\DateTimeInterface $arrivalDate): static
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}

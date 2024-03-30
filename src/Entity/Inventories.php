<?php

namespace App\Entity;

use App\Repository\InventoriesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoriesRepository::class)]
class Inventories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'inventories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $reference = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $channels = null;

    #[ORM\Column(nullable: true)]
    private ?float $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?Product
    {
        return $this->reference;
    }

    public function setReference(?Product $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getChannels(): ?array
    {
        return $this->channels;
    }

    public function setChannels(?array $channels): static
    {
        $this->channels = $channels;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}

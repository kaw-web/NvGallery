<?php

namespace App\Entity;

use App\Repository\InventoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(targetEntity: Inbounds::class, mappedBy: 'Inventory')]
    private Collection $inbounds;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function __construct()
    {
        $this->inbounds = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Inbounds>
     */
    public function getInbounds(): Collection
    {
        return $this->inbounds;
    }

    public function addInbound(Inbounds $inbound): static
    {
        if (!$this->inbounds->contains($inbound)) {
            $this->inbounds->add($inbound);
            $inbound->setInventory($this);
        }

        return $this;
    }

    public function removeInbound(Inbounds $inbound): static
    {
        if ($this->inbounds->removeElement($inbound)) {
            // set the owning side to null (unless already changed)
            if ($inbound->getInventory() === $this) {
                $inbound->setInventory(null);
            }
        }

        return $this;
    }
}

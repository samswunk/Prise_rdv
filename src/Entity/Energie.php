<?php

namespace App\Entity;

use App\Repository\EnergieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EnergieRepository::class)
 */
class Energie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nomEnergie;

    /**
     * @ORM\Column(type="decimal",scale=2)
     */
    private $tarifEnergie;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="energie")
     */
    private $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEnergie(): ?string
    {
        return $this->nomEnergie;
    }

    public function setNomEnergie(string $nomEnergie): self
    {
        $this->nomEnergie = $nomEnergie;

        return $this;
    }

    public function getTarifEnergie(): ?float
    {
        return $this->tarifEnergie;
    }

    public function setTarifEnergie(float $tarifEnergie): self
    {
        $this->tarifEnergie = $tarifEnergie;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setEnergie($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getEnergie() === $this) {
                $booking->setEnergie(null);
            }
        }

        return $this;
    }
}

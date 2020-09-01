<?php

namespace App\Entity;

use App\Repository\CityCoordinatesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityCoordinatesRepository::class)
 */
class CityCoordinates
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $cityAscii;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=4)
     */
    private float $lat;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=4)
     */
    private float $lng;

    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="CityCoordinates")
     */
    private Collection $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCityAscii(): ?string
    {
        return $this->cityAscii;
    }

    public function setCityAscii(string $cityAscii): self
    {
        $this->cityAscii = $cityAscii;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setCityCoordinates($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getCityCoordinates() === $this) {
                $customer->setCityCoordinates(null);
            }
        }

        return $this;
    }
}

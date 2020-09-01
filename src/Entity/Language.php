<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LanguageRepository::class)
 */
class Language
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
    private string $language;

    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="language")
     */
    private Collection $customers;

    /**
     * @ORM\OneToMany(targetEntity=Weather::class, mappedBy="language")
     */
    private Collection $weathers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
        $this->weathers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

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
            $customer->setLanguage($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getLanguage() === $this) {
                $customer->setLanguage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Weather[]
     */
    public function getWeathers(): Collection
    {
        return $this->weathers;
    }

    public function addWeather(Weather $weather): self
    {
        if (!$this->weathers->contains($weather)) {
            $this->weathers[] = $weather;
            $weather->setLanguage($this);
        }

        return $this;
    }

    public function removeWeather(Weather $weather): self
    {
        if ($this->weathers->contains($weather)) {
            $this->weathers->removeElement($weather);
            // set the owning side to null (unless already changed)
            if ($weather->getLanguage() === $this) {
                $weather->setLanguage(null);
            }
        }

        return $this;
    }
}

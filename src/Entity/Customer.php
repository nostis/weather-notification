<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
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
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @ORM\Column(type="time")
     */
    private \DateTimeInterface $notificationHour;

    private string $city;

    /**
     * @ORM\ManyToOne(targetEntity=Language::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private Language $language;

    /**
     * @ORM\ManyToOne(targetEntity=CityCoordinates::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private CityCoordinates $cityCoordinates;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNotificationHour(): ?\DateTimeInterface
    {
        return $this->notificationHour;
    }

    public function setNotificationHour(\DateTimeInterface $notificationHour): self
    {
        $this->notificationHour = $notificationHour;

        return $this;
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

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getCityCoordinates(): ?CityCoordinates
    {
        return $this->cityCoordinates;
    }

    public function setCityCoordinates(?CityCoordinates $cityCoordinates): self
    {
        $this->cityCoordinates = $cityCoordinates;

        return $this;
    }
}

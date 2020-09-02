<?php

namespace App\Entity;

use App\Repository\WeatherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeatherRepository::class)
 */
class Weather
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="time")
     */
    private \DateTimeInterface $sunrise;

    /**
     * @ORM\Column(type="time")
     */
    private \DateTimeInterface $sunset;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $forecastForDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $temperature;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $pressure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $humidity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $windSpeed;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $weatherStatus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $weatherDescription;

    /**
     * @ORM\ManyToOne(targetEntity=Language::class, inversedBy="weathers")
     * @ORM\JoinColumn(nullable=false)
     */
    private Language $language;

    /**
     * @ORM\ManyToOne(targetEntity=CityCoordinates::class, inversedBy="weather")
     * @ORM\JoinColumn(nullable=false)
     */
    private CityCoordinates $cityCoordinates;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $icon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSunrise(): ?\DateTimeInterface
    {
        return $this->sunrise;
    }

    public function setSunrise(\DateTimeInterface $sunrise): self
    {
        $this->sunrise = $sunrise;

        return $this;
    }

    public function getSunset(): ?\DateTimeInterface
    {
        return $this->sunset;
    }

    public function setSunset(\DateTimeInterface $sunset): self
    {
        $this->sunset = $sunset;

        return $this;
    }

    public function getForecastForDate(): ?\DateTimeInterface
    {
        return $this->forecastForDate;
    }

    public function setForecastForDate(\DateTimeInterface $forecastForDate): self
    {
        $this->forecastForDate = $forecastForDate;

        return $this;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(string $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getPressure(): ?string
    {
        return $this->pressure;
    }

    public function setPressure(string $pressure): self
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getHumidity(): ?string
    {
        return $this->humidity;
    }

    public function setHumidity(string $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getWindSpeed(): ?string
    {
        return $this->windSpeed;
    }

    public function setWindSpeed(string $windSpeed): self
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    public function getWeatherStatus(): ?string
    {
        return $this->weatherStatus;
    }

    public function setWeatherStatus(string $weatherStatus): self
    {
        $this->weatherStatus = $weatherStatus;

        return $this;
    }

    public function getWeatherDescription(): ?string
    {
        return $this->weatherDescription;
    }

    public function setWeatherDescription(string $weatherDescription): self
    {
        $this->weatherDescription = $weatherDescription;

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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }
}

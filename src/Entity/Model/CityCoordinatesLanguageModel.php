<?php

namespace App\Entity\Model;

use App\Entity\CityCoordinates;
use App\Entity\Language;

class CityCoordinatesLanguageModel
{
    private CityCoordinates $cityCoordinates;

    private Language $language;

    public function getCityCoordinates(): ?CityCoordinates
    {
        return $this->cityCoordinates;
    }

    public function setCityCoordinates(?CityCoordinates $cityCoordinates): self
    {
        $this->cityCoordinates = $cityCoordinates;

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
}

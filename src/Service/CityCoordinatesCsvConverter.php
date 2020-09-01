<?php

namespace App\Service;

use App\Entity\CityCoordinates;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class CityCoordinatesCsvConverter
{
    private DecoderInterface $decoder;

    public function __construct(DecoderInterface $decoder)
    {
        $this->decoder = $decoder;
    }

    public function getDataConvertedToCityCoordinates(string $data): Collection
    {
        $decoded = $this->decodeFile($data);

        $cityCoordinatesCollection = new ArrayCollection();

        foreach($decoded as $item) {
            $cityCoordinates = new CityCoordinates();
            $cityCoordinates->setCity($item['city']);
            $cityCoordinates->setCityAscii($item['city_ascii']);
            $cityCoordinates->setLat($item['lat']);
            $cityCoordinates->setLng($item['lng']);

            $cityCoordinatesCollection[] = $cityCoordinates;
        }

        return $cityCoordinatesCollection;
    }

    private function decodeFile(string $data): array
    {
        return $this->decoder->decode($data, CsvEncoder::FORMAT);
    }
}
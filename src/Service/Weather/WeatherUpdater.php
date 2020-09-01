<?php


namespace App\Service\Weather;


use App\Controller\Api\WeatherApiController;
use App\Entity\CityCoordinates;
use App\Entity\Customer;
use App\Entity\Language;
use App\Entity\Weather;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class WeatherUpdater
{
    private EntityManagerInterface $entityManager;
    private WeatherApiController $weatherApiController;
    private WeatherApiResponseConverter $weatherApiResponseConverter;

    public function __construct(EntityManagerInterface $entityManager, WeatherApiController $weatherApiController, WeatherApiResponseConverter $weatherApiResponseConverter)
    {
        $this->entityManager = $entityManager;
        $this->weatherApiController = $weatherApiController;
        $this->weatherApiResponseConverter = $weatherApiResponseConverter;
    }

    public function updateAllWeather()
    {
        $this->removeAllWeatherEntities();
    }

    public function updateWeatherForSpecificLocation(CityCoordinates $cityCoordinates, Language $language)
    {

    }

    private function getCitiesWhichRequireUpdate(): Collection
    {
        $cities = new ArrayCollection();
        $customers = $this->entityManager->getRepository(Customer::class)->findAll();

        /**
         * @var Customer $customer
         */
        foreach($customers as $customer) {
            $city = $customer->getCityCoordinates();

            if(!$cities->contains($city)) {
                $cities[] = $city;
            }
        }

        return $cities;
    }

    private function removeAllWeatherEntities()
    {
        $this->entityManager->getRepository(Weather::class)
            ->createQueryBuilder('w')
            ->delete()
            ->getQuery()
            ->execute();
    }
}
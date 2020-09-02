<?php


namespace App\Service\Weather;


use App\Controller\Api\WeatherApiController;
use App\Entity\CityCoordinates;
use App\Entity\Customer;
use App\Entity\Language;
use App\Entity\Model\CityCoordinatesLanguageModel;
use App\Entity\Weather;
use App\Repository\WeatherRepository;
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

        $cityCoordinatesLanguageToUpdate = $this->getCitiesLanguageAssignedToCustomers();

        /**
         * @var CityCoordinatesLanguageModel $cityCoordinateLanguage
         */
        foreach($cityCoordinatesLanguageToUpdate as $cityCoordinateLanguage) {
            $cityCoordinates = $cityCoordinateLanguage->getCityCoordinates();
            $language = $cityCoordinateLanguage->getLanguage();

            $response = $this->weatherApiController->getData($cityCoordinates, $language);

            $todayWeatherForecast = $this->weatherApiResponseConverter->getForecastForToday($response, $language, $cityCoordinates);
            $tomorrowWeatherForecast = $this->weatherApiResponseConverter->getForecastForTomorrow($response, $language, $cityCoordinates);

            $this->entityManager->persist($todayWeatherForecast);
            $this->entityManager->persist($tomorrowWeatherForecast);
        }

        $this->entityManager->flush();
    }

    public function updateWeatherForSpecificLocation(CityCoordinates $cityCoordinates, Language $language)
    {
        $response = $this->weatherApiController->getData($cityCoordinates, $language);

        $todayWeatherForecast = $this->weatherApiResponseConverter->getForecastForToday($response, $language, $cityCoordinates);
        $tomorrowWeatherForecast = $this->weatherApiResponseConverter->getForecastForTomorrow($response, $language, $cityCoordinates);

        /**
         * @var WeatherRepository $weatherRepository
         */
        $weatherRepository = $this->entityManager->getRepository(Weather::class);

        /**
         * @var Weather $todayForecastToDelete | null
         */
        $todayForecastToDelete = $weatherRepository->findOneBy(['forecastForDate' => new \DateTime(), 'cityCoordinates' => $cityCoordinates, 'language' => $language]);

        /**
         * @var Weather $tomorrowForecastToDelete | null
         */
        $tomorrowForecastToDelete = $weatherRepository->findOneBy(['forecastForDate' => (new \DateTime())->add(new \DateInterval('P1D')), 'cityCoordinates' => $cityCoordinates, 'language' => $language]);

        if($todayForecastToDelete !== null) {
            $this->entityManager->remove($todayForecastToDelete);
        }

        if($tomorrowForecastToDelete !== null) {
            $this->entityManager->remove($tomorrowForecastToDelete);
        }

        $this->entityManager->persist($todayWeatherForecast);
        $this->entityManager->persist($tomorrowWeatherForecast);

        $this->entityManager->flush();
    }

    public function getCitiesLanguageAssignedToCustomers(): Collection //of CityCoordinatesLanguageModel
    {
        $citiesLanguage = new ArrayCollection();
        $customers = $this->entityManager->getRepository(Customer::class)->findAll();

        /**
         * @var Customer $customer
         */
        foreach($customers as $customer) {
            $city = $customer->getCityCoordinates();
            $language = $customer->getLanguage();

            $cityCoordinatesLanguage = new CityCoordinatesLanguageModel();
            $cityCoordinatesLanguage->setCityCoordinates($city);
            $cityCoordinatesLanguage->setLanguage($language);

            if(!$citiesLanguage->contains($cityCoordinatesLanguage)) {
                $citiesLanguage[] = $cityCoordinatesLanguage;
            }
        }

        return $citiesLanguage;
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
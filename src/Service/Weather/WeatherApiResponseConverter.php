<?php


namespace App\Service\Weather;


use App\Entity\CityCoordinates;
use App\Entity\Language;
use App\Entity\Weather;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherApiResponseConverter
{
    public function getForecastForToday(ResponseInterface $apiResponseData, Language $language, CityCoordinates $cityCoordinates): Weather
    {
        $response = $apiResponseData->toArray();

        $todayDate = new \DateTime(); //without timezone feature

        foreach($response['daily'] as $dailyWeather) {
            $dateOfWeather = $dailyWeather['dt'];
            $dateOfWeather = (new \DateTime("@$dateOfWeather"))->format('Y-m-d');

            if($todayDate->format('Y-m-d') == $dateOfWeather) {
                return $this->getWeatherForecastDayData($dailyWeather, $language, $cityCoordinates, $todayDate);
            }
        }

        throw new HttpException('400', 'Response from api doesn\'t contain today weather forecast');
    }

    public function getForecastForTomorrow(ResponseInterface $apiResponseData, Language $language, CityCoordinates $cityCoordinates): Weather
    {
        $response = $apiResponseData->toArray();

        $nextDayDate = ((new \DateTime())->add(new \DateInterval('P1D'))); //without timezone feature

        foreach($response['daily'] as $dailyWeather) {
            $dateOfWeather = $dailyWeather['dt'];
            $dateOfWeather = (new \DateTime("@$dateOfWeather"))->format('Y-m-d');

            if($nextDayDate->format('Y-m-d') == $dateOfWeather) {
                return $this->getWeatherForecastDayData($dailyWeather, $language, $cityCoordinates, $nextDayDate);
            }
        }

        throw new HttpException('400', 'Response from api doesn\'t contain next day weather forecast');
    }

    private function getWeatherForecastDayData(array $data, Language $language, CityCoordinates $cityCoordinates, \DateTimeInterface $todayDate): Weather
    {
        $sunrise = $data['sunrise'];
        $sunset = $data['sunset'];

        $weather = new Weather();
        $weather->setLanguage($language);
        $weather->setCityCoordinates($cityCoordinates);
        $weather->setForecastForDate($todayDate);
        $weather->setHumidity($data['humidity']);
        $weather->setPressure($data['pressure']);
        $weather->setSunrise(new \DateTime("@$sunrise"));
        $weather->setSunset(new \DateTime("@$sunset"));
        $weather->setTemperature($data['temp']['day']);
        $weather->setWeatherDescription($data['weather'][0]['description']);
        $weather->setWeatherStatus($data['weather'][0]['main']);
        $weather->setIcon($data['weather'][0]['icon']);
        $weather->setWindSpeed($data['wind_speed']);

        return $weather;
    }
}
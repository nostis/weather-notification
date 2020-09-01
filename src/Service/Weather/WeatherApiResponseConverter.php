<?php


namespace App\Service\Weather;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherApiResponseConverter
{
    public function getForecastForToday(ResponseInterface $apiResponseData)
    {
        $response = $apiResponseData->getContent();
    }

    public function getForecastForNextDay(ResponseInterface $apiResponseData)
    {
        $response = $apiResponseData->getContent();
    }
}
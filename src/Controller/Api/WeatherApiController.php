<?php

namespace App\Controller\Api;

use App\Entity\CityCoordinates;
use App\Entity\Language;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherApiController extends AbstractController
{
    private HttpClientInterface $httpClient;
    private ParameterBagInterface $params;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        $this->httpClient = $httpClient;
        $this->params = $parameterBag;
    }

    public function getData(CityCoordinates $cityCoordinates, Language $language): ResponseInterface
    {
        return $this->httpClient->request('GET', $this->params->get('open_weather_api.url'), [
            'query' => [
                'appid' => $this->params->get('open_weather_api.key'),
                'units' => 'metric',
                'exclude' => 'hourly,minutely,current',
                'lat' => $cityCoordinates->getLat(),
                'lon' => $cityCoordinates->getLng(),
                'lang' => $language->getCode()
            ]
        ]);
    }
}
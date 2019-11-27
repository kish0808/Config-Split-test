<?php

namespace Drupal\d8_activity;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactory;
use Exception;
use GuzzleHttp\Client;

class WeatherParser{


    private $configFactory;
    private $client;

    public function __construct(Client $client, ConfigFactory $config_factory) {
        $this->client = $client;
        $this->configFactory = $config_factory;
    }

    /**
     * Helper function to fetch weather data based on the city name.
     *
     * @param $city
     * @return mixed
     */

    public function Weatherinfo($city) {

        $app_id = $this->configFactory->get('d8_activity.weather_config')->get('app_id');
       // kint($app_id);
        $url_string = "https://api.openweathermap.org/data/2.5/weather?q=" . $city . "&appid=" . $app_id;
       // kint($url_string);

        try {
            $rest= $this->client->get($url_string);
            return Json::decode($rest->getBody()->getContents())['main'];
        }
        catch (Exception $e) {
            return 'An error occured while fetching data';
        }
    }
}
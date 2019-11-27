<?php

namespace Drupal\d8_activity\Plugin\Block;


use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\d8_activity\WeatherParser;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "weather_block",
 *   admin_label = @Translation("Weather block"),
 *   category = @Translation("weather World"),
 * )
 */
class WeatherBlock extends BlockBase  implements ContainerFactoryPluginInterface{

    /**
     * {@inheritdoc}
     *
     *
     *
     */


   private $weather_Parser;


    public function __construct(array $configuration, $plugin_id, $plugin_definition, WeatherParser $weather_Parser) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->weather_Parser = $weather_Parser;
    }

    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('d8_activity.weather_parser')
        );
    }

    public function build() {

        $city_weather_data = $this->weather_Parser->Weatherinfo($this->configuration['city']);
        //kint($city_weather_data);

        return [
            '#theme' => 'weather_info',
            '#temp' => $city_weather_data['temp'],
            '#pressure' => $city_weather_data['pressure'],
            '#humidity' => $city_weather_data['humidity'],
            '#temp_min' => $city_weather_data['temp_min'],
            '#temp_max' => $city_weather_data['temp_max'],
            '#attached' => [
                'library' => 'd8_activity/weather-widget',
            ],
        ];
    }

    public function blockForm($form, FormStateInterface $form_state) {
        return [
            'city' => [
                '#type' => 'textfield',
                '#title' => 'City config for this block',
                '#default_value' => $this->getConfiguration()['city'],
            ],
        ];
    }


    public function blockSubmit($form, FormStateInterface $form_state) {

        $this->setConfigurationValue('city', $form_state->getValue('city'));
    }
    /**
     * @inheritDoc
     */

}



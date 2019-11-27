<?php
namespace Drupal\d8_activity\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


class D8ConfigForm extends ConfigFormBase {

    protected function getEditableConfigNames() {
        return [
            'd8_activity.weather_config',
        ];
    }


    public function getFormId() {
        return 'weather_config_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['app_id'] = [
            '#type' => 'textfield',
            '#title' => 'App id',
            '#description' => 'App id for my Openweathermap access.',
            '#default_value' => $this->config('d8_custom.weather_config')->get('app_id'),
        ];
        return parent::buildForm($form, $form_state);
    }
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $this->config('d8_activity.weather_config')
            ->set('app_id', $form_state->getValue('app_id'))
            ->save();
        parent::submitForm($form, $form_state);
    }
}
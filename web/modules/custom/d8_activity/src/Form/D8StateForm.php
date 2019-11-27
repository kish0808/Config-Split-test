<?php

namespace Drupal\d8_activity\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class D8StateForm extends FormBase {

    public function getFormId() {
        return 'state_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['Qualification'] = [
            '#type' => 'select',
            '#title' => $this->t('Select'),
            '#options' => [
                'UG' => $this->t('U.G'),
                'PG' => $this->t('P.G'),
                'other' => $this->t('Other'),
            ],
            '#attributes' => [

                'name' => 'field_select_Qualification',
            ],
        ];
        $form['custom_field'] = [
            '#type' => 'textfield',
            '#size' => '60',
            '#placeholder' => 'please specify',
            '#attributes' => [
                'id' => 'custom-field',
            ],
            '#states' => [
                'visible' => [
                    ':input[name="field_select_Qualification"]' => ['value' => 'other'],
                ],
            ],
        ];
        $form['country'] = [
            '#type' => 'markup',
            '#prefix' => '<div id="country">',
            '#suffix' => '</div>',
            '#markup' => '',
        ];

        $form['country_list'] = [
            '#type' => 'select',
            '#title' => 'Choose country First',
            '#options' =>[
                0 => 'Choose',
                1 => 'India',
                2 => 'UK'
                ],
            '#default_value' => 0,
        ];

        $form['india_states'] = [
            '#type' => 'select',
            '#description' => t("sates in india."),
            '#states' => [
                'visible' => [
                    ':input[name="country_list"]' => ['value' => '1'],
                ],
            ],
            '#options' =>[
                0 => 'Choose',
                1 => 'Maharashtra',
                2 => 'Goa',
                3 => 'Tamil-nadu'],
            '#default_value' => 0,
        ];

        $form['UK_states'] = [
            '#type' => 'select',
            '#description' => t("sates in uk."),
            '#states' => [
                'visible' => [
                    ':input[name="country_list"]' => ['value' => '2'],
                ],
            ],
            '#options' =>[
                0 => 'Choose',
                1 => 'England',
                2 => 'London',
                3=> 'Scotland'],
            '#default_value' => 0,
        ];


        $form['submit'] =[
            '#type' => 'submit',
            '#value' => 'Submit',
            '#executes_submit_callback' => FALSE,
            '#ajax' => [
                'callback' => '::advanced_form_callback',
                'wrapper' => 'country',
            ],
        ];



        return $form;

    }


    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);
    }


    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // Display result.
        foreach ($form_state->getValues() as $key => $value) {
            drupal_set_message($key . ': ' . $value);
        }
    }

    function advanced_form_callback($form, &$form_state) {

        $city_name = form_result_helper($form_state);
        $element = $form['country'];
        $element['#markup'] = t('You submitted @city', ['@city' => $city_name]);

        return $element;

    }

    function form_result_helper($form_state) {

        $india_state = $form_state['values']['country_list'];
        $city_name = t('No City Selected');
        $city = '';
        switch ($india_state) {
            case 1 :
                $city = $form_state['values']['india_states'];
                switch($city) {
                    case 0:
                        $city_name = 'chose';
                        break;
                    case 1:
                        $city_name = 'Maharashtra';
                        break;
                    case 2:
                        $city_name = 'Goa';
                        break;
                    case 3:
                        $city_name = 'Tamil-nadu';
                }
                break;
            case 2 :
                $city = $form_state['values']['UK_states'];
                switch ($city) {
                    case 0:
                        $city_name = 'chose';
                        break;
                    case 1:
                        $city_name = 'England';
                        break;
                    case 2:
                        $city_name = 'Scotland';
                        break;
                    case 3:
                        $city_name = '';
                }
                break;
        }
        return $city_name;
    }

}
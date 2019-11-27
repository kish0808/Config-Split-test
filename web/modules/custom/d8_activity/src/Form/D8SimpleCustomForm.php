<?php
namespace Drupal\d8_activity\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\d8_activity\DicUserEntry;
use Symfony\Component\DependencyInjection\ContainerInterface;

class D8SimpleCustomForm extends FormBase {


    private $dbuser;

    public function __construct(DicUserEntry $db_user) {
        $this->dbuser = $db_user;
    }

    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('d8_activity.dic_user_entry')
        );
    }
    public function getFormId() {
        return 'd8_simple_form';
    }
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['name'] = [
            '#type' => 'textfield',
            '#title' => 'Enter your Name',
            '#description' => 'Name must have at least 5 characters',
            '#default_value' => $this->dbuser->read(),
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'Validate First Name',
        ];
        return $form;
    }
    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);
        if (strlen($form_state->getValue('name')) < 5) {
            $form_state->setErrorByName('name', 'Name must be at least 5 characters long!');
        }
    }
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $this->dbuser->write($form_state->getValue('name'));
        drupal_set_message('Name value submitted successfully: ' . $form_state->getValue('name'));
    }



}
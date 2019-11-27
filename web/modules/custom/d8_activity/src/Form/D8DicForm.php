<?php

namespace  Drupal\d8_activity\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\d8_activity\DicUserEntry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerInterface;


Class D8DicForm extends FormBase
{
    protected $messenger;
    protected $dbuser;

    public function __construct(DicUserEntry $dbuser, MessengerInterface $messenger)
    {

        $this->messenger = $messenger;
        $this-> dbuser = $dbuser;
    }

    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('d8_activity.dic_user_entry'),
            $container->get('messenger')
        );
    }

    public function getFormId()
    {
        return Dic_from;
    }
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['first_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Please Enter your First Name'),
            '#default_value' => $this->dbuser->read('first_name'),
        ];

        $form['last_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t(' Enter your Name Last Name'),
           //'#default_value' => $this->dbuser->read('last_name'),
        ];
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
        ];
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);
        if(strlen($form_state->getValues('first_name'))<5 ){

            $this->messenger->addMessage('please Enter more than 5 charater');
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $userdata = $form_state->getValues();
        $this->dbuser->write( $userdata);
        $this->messenger->addMessage('Name value submitted successfully: ' . $userdata('first_name').$userdata('last_name'));

    }
}
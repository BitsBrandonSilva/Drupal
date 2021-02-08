<?php

namespace Drupal\forcontu_hello_forms\Form;

use Drupal\Component\Utility\EmailValidator;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

//para poder acceder al contenedor de servicios
use Symfony\Component\DependencyInjection\ContainerInterface;

//para acceder al servicio database
use Drupal\Core\Database\Connection;

//para accede al servicio current_user
use Drupal\Core\Session\AccountInterface;

class Simple extends FormBase {

  protected $database;
  protected $currentUser;
  protected $emailValidator;

  public function __construct(Connection $database,
                              AccountInterface $current_user,
                              EmailValidator $email_validator)
  {
    $this->database = $database;
    $this->currentUser = $current_user;
    $this->emailValidator = $email_validator;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('database'),
      $container->get('current_user'),
      $container->get('email.validator'),
    );
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['guitar'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Guitar brand'),
      '#description' => $this->t('Guitar'),
      '#required' => TRUE,
    ];

    $form['type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Type'),
      '#description' => $this->t('Type of guitar.'),
      '#required' => TRUE,
    ];

    $form['cuerdas'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Strings'),
      '#description' => $this->t('Strings of the guitar.'),
      '#required' => TRUE,
    ];

    $form['user_email'] = [
      '#type' => 'email',
      '#title' => $this->t('User email'),
      '#description' => $this->t('Your email.'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    $form['actions']['delete'] = [
      '#type' => 'delete',
      '#value' => $this->t('Delete'),
    ];

    return $form;
  }
  public function getFormId() {
    return 'forcontu_hello_forms_simple';
  }
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $title = $form_state->getValue('type');
    if(strlen($title) < 5) {
      $form_state->setErrorByName('type', $this->t('The type must
        be at least 5 characters long.'));
    }

    $email = $form_state->getValue('user_email');
    if(!$this->emailValidator->isValid($email)) {
      $form_state->setErrorByName('user_email', $this->t('%email is
        not a valid email address.', ['%email' => $email]));
    }

  }
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->database->insert('forcontu_hello_test_one')
      ->fields([
        'guitar' => $form_state->getValue('guitar'),
        'type' => $form_state->getValue('type'),
        'cuerdas' => $form_state->getValue('cuerdas'),
        'email' => $form_state->getValue('email'),
        'ip' => \Drupal::request()->getClientIP(),
      ])
      ->execute();
  }

}

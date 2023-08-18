<?php

namespace Drupal\assesmentone\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Extending class.
 */
class CustomForm extends FormBase {

  /**
   * Function.
   */
  public function getFormId() {
    return 'assesmentone_form';
  }

  /**
   * Function.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['firstname'] = [
      '#type' => 'textfield',
      '#title' => 'firstname',
      '#required' => TRUE,
    ];
    $form['lastname'] = [
      '#type' => 'textfield',
      '#title' => 'lastname',
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => 'email',
      '#required' => TRUE,
    ];
    $form['gender'] = [
      '#type' => 'textfield',
      '#title' => 'gender',
      '#required' => TRUE,
    ];
    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => 'phone',
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'submit',
    ];

    return $form;

  }

  /**
   * Function.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Function.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Function.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->database->insert("assessment_information")->fields([
      'firstname' => $form_state->getValue('firstname'),
      'lastname' => $form_state->getValue('lastname'),
      'email' => $form_state->getValue('email'),
      'gender' => $form_state->getValue('gender'),
      'phone' => $form_state->getValue('phone'),
    ])->execute();
  }

}

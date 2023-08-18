<?php

namespace Drupal\dropdown_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements the example form.
 */
class DropdownForm extends FormBase {
  /**
   * The Messenger service.
   *
   * @var Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs DropdownForm.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database service.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dropdown_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $electronic_item = $form_state->getValue("electronic_item");
    $models = $form_state->getValue("models");

    $form['electronic_item'] = [
      '#type' => 'select',
      '#title' => 'Choose Item',
      '#options' => $this->getElectronicItemOptions(),
      '#empty_option' => '-select-',
      '#ajax' => [
        'callback' => '::dropdownCallback',
        'wrapper' => 'field-container',
        'event' => 'change',
      ],
    ];

    $form['models'] = [
      '#type' => 'select',
      '#title' => 'Choose Model',
      '#options' => $this->getModelOptions($electronic_item),
      '#empty_option' => '-select-',
      '#prefix' => '<div id="field-container">',
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => '::dropdownCallback',
        'wrapper' => 'dist-container',
        'event' => 'change',
      ],
    ];

    $form['color'] = [
      '#type' => 'select',
      '#title' => 'Choose Color',
      '#options' => $this->getColorOptions($models),
      '#empty_option' => '-select-',
      '#prefix' => '<div id="dist-container">',
      '#suffix' => '</div>',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Handle form submission if needed.
  }

  /**
   * Function for ajax dropdown.
   */
  public function dropdownCallback(array &$form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    $triggering_element_name = $triggering_element['#name'];

    if ($triggering_element_name === 'electronic_item') {
      return $form['models'];
    }
    elseif ($triggering_element_name === 'models') {
      return $form['color'];
    }
  }

  /**
   * Function to retrieve electronic_item options.
   */
  public function getElectronicItemOptions() {
    $query = $this->database->select('electronic_items', 'e');
    $query->fields('e', ['id', 'name']);
    $result = $query->execute();
    $options = [];
    foreach ($result as $item) {
      $options[$item->id] = $item->name;
    }
    return $options;
  }

  /**
   * Function to retrieve model options.
   */
  public function getModelOptions($electronic_item) {
    $query = $this->database->select('models', 'm');
    $query->fields('m', ['id', 'name']);
    $query->condition('m.electronic_items_id', $electronic_item);
    $result = $query->execute();
    $options = [];
    foreach ($result as $model) {
      $options[$model->id] = $model->name;
    }
    return $options;
  }

  /**
   * Function to retrieve color options.
   */
  public function getColorOptions($models) {
    $query = $this->database->select('color', 'c');
    $query->fields('c', ['id', 'name']);
    $query->condition('c.models_id', $models, 'IN');
    $result = $query->execute();
    $options = [];
    foreach ($result as $color) {
      $options[$color->id] = $color->name;
    }
    return $options;
  }

}

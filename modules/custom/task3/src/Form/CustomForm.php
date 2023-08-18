<?php

namespace Drupal\task3\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Database\Connection;

/**
 * Extending base class.
 */
class CustomForm extends FormBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;
  /**
   * The account service.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * CustomForm constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, AccountInterface $account, Connection $database) {
    $this->entityTypeManager = $entityTypeManager;
    $this->account = $account;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_form_get_user_details';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $title = NULL) {
    $user = $this->entityTypeManager->getStorage('user')->load($this->account->id());
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => 'Title',
      '#required' => TRUE,
      '#default_value' => $title,
      // '#placeholder' => 'Title',
    ];

    $form['user'] = [
      '#type' => 'entity_autocomplete',
      '#title' => 'User',
      '#target_type' => 'user',
      '#default_value' => $user,
      '#required' => TRUE,
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
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $user_id = $form_state->getValue('user');
    // Load the user entity using the entity type manager.
    $user = $this->entityTypeManager->getStorage('user')->load($user_id);
    // Validate the selected user.
    if (empty($user)) {
      $form_state->setErrorByName('user', $this->t('Invalid user selected.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $title = $form_state->getValue('title');
    $user_id = $form_state->getValue('user');
    $user = $this->entityTypeManager->getStorage('user')->load($user_id);
    if ($user) {
      $data = [
        'title' => $title,
        'user_id' => $user_id,
      ];
      $this->database->insert('user_information')->fields($data)->execute();
    }
  }

}

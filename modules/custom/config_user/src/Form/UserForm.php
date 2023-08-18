<?php

namespace Drupal\config_user\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\Entity\Role;

/**
 * Extending base class.
 */
class UserForm extends ConfigFormBase {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * Constructs an AutoParagraphForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entityTypeManager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_user_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['config_user.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $roles = Role::loadMultiple();
    $role_options = [];

    foreach ($roles as $role_id => $role) {
      $role_options[$role_id] = $role->label();
    }

    $config = $this->config('config_user.settings');

    $existingContentTypeOptions = $this->getExistingContentTypes();

    $form['selected_role'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Role'),
      '#options' => $role_options,
      '#default_value' => $config->get('selected_role'),
    ];

    $form['content_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Content Types'),
      '#options' => $existingContentTypeOptions,
      '#empty_option' => $this->t('- Select an existing content type -'),
      '#default_value' => $config->get('content_types', []),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('config_user.settings');

    $config->set('content_types', $form_state->getValue('content_types'));
    $config->set('selected_role', $form_state->getValue('selected_role'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * Returns a list of all the content types currently installed.
   *
   * @return array
   *   An array of content types.
   */
  public function getExistingContentTypes() {
    $types = [];
    $contentTypes = $this->entityTypeManager->getStorage('node_type')->loadMultiple();
    foreach ($contentTypes as $contentType) {
      $types[$contentType->id()] = $contentType->label();
    }
    return $types;
  }

}

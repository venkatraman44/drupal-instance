<?php declare(strict_types = 1);

namespace Drupal\configurationtask\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\taxonomy\Entity\Term;

/**
 * Configure Config assessment settings for this site.
 */
class ConfiForm extends ConfigFormBase {

  /**
   * The configuration factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new ConfiForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'configurationtask_confiform';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['configurationtask.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->configFactory->get('configurationtask.settings');
    $tag_name = $config->get('tags_vocabulary');
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('TITLE'),
      '#default_value' => $this->config('configurationtask.settings')->get('title'),
    ];
    $form['advanced'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Advanced'),
      '#default_value' => $this->config('configurationtask.settings')->get('advanced'),
    ];
    $form['tags_vocabulary'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Tags'),
      '#target_type' => 'taxonomy_term',
      '#default_value' => $this->entityTypeManager->getStorage('taxonomy_term')->load($tag_name),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('configurationtask.settings')
      ->set('title', $form_state->getValue('title'))
      ->set('advanced', $form_state->getValue('advanced'))
      ->set('tags_vocabulary', $form_state->getValue('tags_vocabulary'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}

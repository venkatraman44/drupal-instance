<?php

declare(strict_types = 1);

namespace Drupal\thirteenth_form_with_checkbox\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Thirteenth form with checkbox form.
 */
final class CustomForm extends FormBase {
  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new CustomForm object.
   *
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger service.
   */
  public function __construct(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('logger.factory')->get('thirteenth_form_with_checkbox')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'thirteenth_form_with_checkbox_custom';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['#attached']['library'][] = 'thirteenth_form_with_checkbox/checkbox';
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('firstname'),
      '#required' => TRUE,
    ];

    $form['last_name_box'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('no last name'),
      '#attributes' => ['id' => 'no-last-name'],
    ];
    $form['last_name_wrapper'] = [
      '#prefix' => '<div id="last-name-wrapper">',
      '#suffix' => '</div>',
    ];
    $form['last_name_wrapper']['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('lastname'),
      '#states' => [
        'visible' => [
          ':input[name="last_name_box"]' => ['checked' => FALSE],
        ],
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->logger->warning('Form submitted');
    $this->messenger()->addStatus($this->t('submitted'));
  }

}

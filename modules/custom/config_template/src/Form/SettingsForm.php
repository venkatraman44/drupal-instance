<?php

declare(strict_types = 1);

namespace Drupal\config_template\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure config_template settings for this site.
 */
final class SettingsForm extends ConfigFormBase {

  const CONFIG = "config_template.settings";

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'config_template_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      static::CONFIG,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config(static::CONFIG);
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#required' => TRUE,
      '#default_value' => $this->config('config_template.settings')->get('title'),
    ];

    $text_format = 'full_html';
    if ($this->config('config_template.settings')->get('paragraph')['format']) {
      $text_format = $this->config('config_template.settings')->get('paragraph')['format'];
    }

    $form['paragraph'] = [
      '#type' => 'text_format',
      '#title' => $this->t('paragraph'),
      '#required' => TRUE,
      '#format' => $text_format,
      '#default_value' => $this->config('config_template.settings')->get('paragraph'),
    ];

    $form['color_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Color Code'),
      '#required' => TRUE,
      '#default_value' => $this->config('config_template.settings')->get('color_code'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $config = $this->config(static::CONFIG);
    $config->set("title", $form_state->getValue('title'));
    $config->set("paragraph", $form_state->getValue('paragraph'));
    $config->set("color_code", $form_state->getValue('color_code'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

}

<?php

namespace Drupal\mail_cron\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements the example form.
 */
class TokenForm extends ConfigFormBase {
  const RESULT = "mail_cron.settings";

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'token_form';
  }

  /**
   * Function.
   */
  protected function getEditableConfigNames() {
    return [
      static::RESULT,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::RESULT);
    $form['subject'] = [
      '#type' => 'textfield',
      '#title' => 'Subject',
      '#default_value' => $config->get("subject"),
    ];
    $form['text'] = [
      '#type' => 'textfield',
      '#title' => 'Text',
      '#default_value' => $config->get("text"),
    ];

    if (\Drupal::moduleHandler()->moduleExists('token')) {
      $form['tokens'] = [
        '#title' => $this->t('Tokens'),
        '#type' => 'container',
      ];
      $form['tokens']['help'] = [
        '#theme' => 'token_tree_link',
        '#token_types' => [
          'node',
        ],
        '#global_types' => FALSE,
        '#dialog' => TRUE,
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config(static::RESULT);
    $config->set("subject", $form_state->getValue('subject'));
    $config->set("text", $form_state->getValue('text'));
    $config->save();
  }

}

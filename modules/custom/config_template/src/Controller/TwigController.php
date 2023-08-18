<?php

namespace Drupal\config_template\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for config_template routes.
 */
class TwigController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function twig() {
    $config = $this->config('config_template.settings');
    $title = $config->get('title');
    $paragraph = $config->get('paragraph')['value'];
    $color_code = $config->get('color_code');

    return [
      '#theme' => 'new_template',
      '#paragraph' => $paragraph,
      '#title' => $title,
      '#color_code' => $color_code,
    ];
  }

}

<?php

namespace Drupal\task1\Controller;

// Namespace of this file.
// Use of controllerbase.
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Use of our custom service.
 */
class CustomController extends ControllerBase {

  /**
   * Function task1.
   */
  public function task1() {
    $node = Node::load(1);
    if ($node) {
      if ($node->getType() === 'shapes') {
        $shape = $node->getTitle();
        $color_tid = $node->get('field_color')->entity;
        $term = $color_tid->getName();
        $userid = $color_tid->get('field_gil')->entity;
        $user = $userid->getDisplayName();
        $build = [
          '#markup' => "$shape | $term | $user",
        ];
        return $build;
      }
    }
  }

}

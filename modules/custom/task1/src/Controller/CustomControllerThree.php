<?php

namespace Drupal\task1\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Use of our custom service.
 */
class CustomControllerThree extends ControllerBase {

  /**
   * This is the function hello.
   */
  public function task3() {
    $node = Node::load(1);
    if ($node->getType() === 'shapes') {
      $shape = $node->getTitle();
      $term = '';
      $color_tid = $node->get('field_color')->referencedEntities();
      if (!empty($color_tid)) {
        $taxonomy_term = reset($color_tid);
        $term = $taxonomy_term->getName();
      }
      $user = '';
      $userid = $taxonomy_term->get('field_gil')->referencedEntities();
      if (!empty($userid)) {
        $user_name = reset($userid);
        $user = $user_name->getDisplayName();
      }
      return [
        '#markup' => "$shape | $term | $user",
      ];
    }
  }

}

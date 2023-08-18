<?php declare(strict_types = 1);

namespace Drupal\duplicate\Controller;

use Drupal\node\Entity\Node;
use Drupal\quick_node_clone\Entity\QuickNodeCloneEntityFormBuilder;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\node\Controller\NodeController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DuplicateController extends NodeController {

  /**
   * The entity form builder.
   *
   * @var \Drupal\quick_node_clone\Form\QuickNodeCloneEntityFormBuilder
   */
  protected $qncEntityFormBuilder;

  /**
   * Constructs a NodeController object.
   *
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository.
   * @param \Drupal\quick_node_clone\Entity\QuickNodeCloneEntityFormBuilder $entity_form_builder
   *   The entity form builder.
   */
  public function __construct(DateFormatterInterface $date_formatter, RendererInterface $renderer, EntityRepositoryInterface $entity_repository, QuickNodeCloneEntityFormBuilder $entity_form_builder) {
    parent::__construct($date_formatter, $renderer, $entity_repository);
    $this->qncEntityFormBuilder = $entity_form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('renderer'),
      $container->get('entity.repository'),
      $container->get('quick_node_clone.entity.form_builder')
    );
  }

  /**
   * Retrieves the entity form builder.
   *
   * @return \Drupal\quick_node_clone\Form\QuickNodeCloneFormBuilder
   *   The entity form builder.
   */
  protected function entityFormBuilder() {
    return $this->qncEntityFormBuilder;
  }

  /**
   * Provides the node submission form.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node entity to clone.
   *
   * @return array
   *   A node submission form.
   */
  public function cloneNode() {
    $node = Node::load(2);
    if (!empty($node)) {
      $form = $this->entityFormBuilder()->getForm($node, 'quick_node_clone');
      return $form;
    }
    else {
      throw new NotFoundHttpException();
    }
  }
}



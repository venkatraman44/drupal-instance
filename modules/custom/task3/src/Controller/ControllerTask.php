<?php

namespace Drupal\task3\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\node\Entity\Node;
use Drupal\task3\Form\CustomForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller to handle tasks.
 */
class ControllerTask extends ControllerBase {

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * ControllerTask constructor.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $formBuilder
   *   The form builder service.
   */
  public function __construct(FormBuilderInterface $formBuilder) {
    $this->formBuilder = $formBuilder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder')
    );
  }

  /**
   * Render the custom form.
   *
   * @return array
   *   A renderable array containing the form.
   */
  public function newTask(node $node) {
    $title = $node->getTitle();
    // print_r($title); exit;
    // Pass the title as an option to the form.
    $form = $this->formBuilder->getForm(CustomForm::class, $title);

    return $form;
  }

}

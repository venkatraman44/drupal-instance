<?php

namespace Drupal\tasksix\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Some comment.
 */
class HeaderRemover implements EventSubscriberInterface {

  /**
   * Some comment.
   */
  public function onKernelResponse(ResponseEvent $event) {
    $response = $event->getResponse();
    $response->headers->remove('X-Generator');
  }

  /**
   * Some comment.
   */
  public static function getSubscribedEvents() {
    // $events[KernelEvents::RESPONSE][] = array('onKernelResponse', -10);
    // return $events;
    return [
      KernelEvents::RESPONSE => ['onKernelResponse'],

    ];
  }

}

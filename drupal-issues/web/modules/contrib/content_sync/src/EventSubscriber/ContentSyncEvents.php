<?php

namespace Drupal\content_sync\EventSubscriber;

use Drupal\Core\Entity\EntityTypeEvent;
use Drupal\Core\Entity\EntityTypeEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Create a content subscriber.
 */
class ContentSyncEvents implements EventSubscriberInterface {

  /**
   * This method is called whenever the event is dispatched.
   *
   * @param \Drupal\Core\Entity\EntityTypeEvent $event
   *   The Event to process.
   */
  public function onContentSyncCreate(EntityTypeEvent $event) {
    \Drupal::logger('content_sync')->notice("Create Event");
  }

  /**
   * Registers the methods in this class that should be listeners.
   *
   * @return array
   *   An array of event listener definitions.
   */
  public static function getSubscribedEvents() {
    $events[EntityTypeEvents::CREATE][] = ['onContentSyncCreate', 40];
    return $events;
  }

}

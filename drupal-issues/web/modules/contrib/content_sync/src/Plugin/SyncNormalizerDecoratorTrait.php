<?php

namespace Drupal\content_sync\Plugin;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Sync Normalizer Decorator.
 */
trait SyncNormalizerDecoratorTrait {

  /**
   * Decorate Normalization.
   */
  protected function decorateNormalization(array &$normalized_entity, ContentEntityInterface $entity, $format, array $context = []) {
    $plugins = $this->getDecoratorManager()->getDefinitions();
    $v = $plugins['id_cleaner'];
    unset($plugins['id_cleaner']);
    $plugins['id_cleaner'] = $v;
    foreach ($plugins as $decorator) {
      /** @var SyncNormalizerDecoratorInterface $instance */
      $instance = $this->getDecoratorManager()->createInstance($decorator['id']);
      $instance->decorateNormalization($normalized_entity, $entity, $format, $context);
    }
  }

  /**
   * Decorate Denormalization.
   */
  protected function decorateDenormalization(array &$normalized_entity, $type, $format, array $context = []) {
    $plugins = $this->getDecoratorManager()->getDefinitions();
    foreach ($plugins as $decorator) {
      /** @var SyncNormalizerDecoratorInterface $instance */
      $instance = $this->getDecoratorManager()->createInstance($decorator['id']);
      $instance->decorateDenormalization($normalized_entity, $type, $format, $context);
    }
  }

  /**
   * Decorate Denormalized Entity.
   */
  protected function decorateDenormalizedEntity(ContentEntityInterface $entity, array $normalized_entity, $format, array $context = []) {
    $plugins = $this->getDecoratorManager()->getDefinitions();
    foreach ($plugins as $decorator) {
      /** @var SyncNormalizerDecoratorInterface $instance */
      $instance = $this->getDecoratorManager()->createInstance($decorator['id']);
      $instance->decorateDenormalizedEntity($entity, $normalized_entity, $format, $context);
    }
  }

  /**
   * Decorator Manager.
   *
   * @return SyncNormalizerDecoratorManager
   *   return new SyncNormalizerDecoratorManager
   */
  abstract protected function getDecoratorManager();

}

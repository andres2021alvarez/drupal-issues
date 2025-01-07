<?php

namespace Drupal\content_sync\Exporter;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Content Exporter Interface.
 */
interface ContentExporterInterface {

  /**
   * Exports the given entity.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   Entity interface content entity.
   * @param array $context
   *   Context array.
   *
   * @return array
   *   Exported entities
   */
  public function exportEntity(ContentEntityInterface $entity, array $context = []);

}

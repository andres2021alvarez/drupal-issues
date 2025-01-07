<?php

namespace Drupal\content_sync\Importer;

/**
 * Content Importer Interface.
 */
interface ContentImporterInterface {

  /**
   * Import content entity.
   *
   * @param mixed $decoded_entity
   *   Entity to import decoded content.
   * @param array $context
   *   Context to use for processing.
   *
   * @return \Drupal\Core\Entity\ContentEntityInterface
   *   Returns the decoded entity
   */
  public function importEntity($decoded_entity, $context = []);

}

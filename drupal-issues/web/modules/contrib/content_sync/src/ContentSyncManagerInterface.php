<?php

namespace Drupal\content_sync;

/**
 * Interface Content Sync Manager Interface.
 *
 * @package Drupal\content_sync
 */
interface ContentSyncManagerInterface {

  /**
   * Get Content Importer.
   *
   * @return \Drupal\content_sync\Importer\ContentImporterInterface
   *   the Importer instance  that will be used.
   */
  public function getContentImporter();

  /**
   * Get Content Exporter.
   *
   * @return \Drupal\content_sync\Exporter\ContentExporterInterface
   *   the content  exporter instance that will be used
   */
  public function getContentExporter();

  /**
   * Get Serializer.
   *
   * @return \Symfony\Component\Serializer\Serializer
   *   the serializer instance  that will be used
   */
  public function getSerializer();

  /**
   * Get Entity Type Manager.
   *
   * @return \Drupal\Core\Entity\EntityTypeManagerInterface
   *   the EntityTypeManager instance that will be used
   */
  public function getEntityTypeManager();

}

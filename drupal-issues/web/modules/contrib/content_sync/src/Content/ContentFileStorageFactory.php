<?php

namespace Drupal\content_sync\Content;

/**
 * Provides a factory for creating content file storage objects.
 */
class ContentFileStorageFactory {

  /**
   * Returns a FileStorage object working with the active content directory.
   *
   * @return \Drupal\Core\Config\FileStorage
   *   The active content directory
   */
  public static function getActive() {
    // Load the class from a different namespace.
    $class = "Drupal\\Core\\Config\\FileStorage";
    return new $class(content_sync_get_content_directory('active') . "/entities");
  }

  /**
   * Returns a FileStorage object working with the sync content directory.
   *
   * @return \Drupal\Core\Config\FileStorage
   *   The active content directory
   */
  public static function getSync() {
    // Load the class from a different namespace.
    $class = "Drupal\\Core\\Config\\FileStorage";
    return new $class(content_sync_get_content_directory('sync') . "/entities");
  }

}

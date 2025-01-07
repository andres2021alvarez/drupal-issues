<?php

namespace Drupal\content_sync\Exporter;

use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Entity\ContentEntityInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Content Exporter.
 */
class ContentExporter implements ContentExporterInterface {


  /**
   * This property is used to specify the serialization format.
   *
   * @var string
   */
  protected $format = 'yaml';

  /**
   * This property is used to store an instance of the Symfony Serializer.
   *
   * @var mixed
   */
  protected $serializer;

  /**
   * This property is initialized as an empty array.
   *
   * @var array
   */
  protected $context = [];

  /**
   * ContentExporter constructor.
   */
  public function __construct(Serializer $serializer) {
    $this->serializer = $serializer;
  }

  /**
   * {@inheritdoc}
   */
  public function exportEntity(ContentEntityInterface $entity, array $context = []) {
    $context = $this->context + $context;
    $context += [
      'content_sync' => TRUE,
    ];
    // Allows to know to normalizers that this is content sync generated entity.
    $entity->is_content_sync = TRUE;
    $normalized_entity = $this->serializer->serialize($entity, $this->format, $context);
    $yaml_parsed = Yaml::decode($normalized_entity);
    $lang_default = $entity->language()->getId();
    foreach ($entity->getTranslationLanguages() as $langcode => $language) {
      // Verify that it is not the default langcode.
      if ($langcode != $lang_default) {
        if ($entity->hasTranslation($langcode)) {
          $entity_translated = $entity->getTranslation($langcode);
          $normalized_entity_translations = $this->serializer->serialize($entity_translated, $this->format, $context);
          $yaml_parsed['_translations'][$langcode] = Yaml::decode($normalized_entity_translations);
        }
      }
    }
    return Yaml::encode($yaml_parsed);
  }

  /**
   * Returns the format property of the object.
   *
   * @return string
   *   Property of the object is being returned.
   */
  public function getFormat() {
    return $this->format;
  }

  /**
   * Returns the serializer property of the object.
   *
   * @return mixed
   *   property is being returned.
   */
  public function getSerializer() {
    return $this->serializer;
  }

}

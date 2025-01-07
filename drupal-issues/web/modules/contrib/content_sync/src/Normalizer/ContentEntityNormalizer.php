<?php

namespace Drupal\content_sync\Normalizer;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\content_sync\Plugin\SyncNormalizerDecoratorManager;
use Drupal\content_sync\Plugin\SyncNormalizerDecoratorTrait;
use Drupal\serialization\Normalizer\ContentEntityNormalizer as BaseContentEntityNormalizer;

/**
 * Adds the file URI to embedded file entities.
 */
class ContentEntityNormalizer extends BaseContentEntityNormalizer {

  use SyncNormalizerDecoratorTrait;

  /**
   * Plugin constructor decorator.
   *
   * @var \Drupal\content_sync\Plugin\SyncNormalizerDecoratorManager
   */
  protected $decoratorManager;

  /**
   * Constructs an EntityNormalizer object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entity_type_repository
   *   The entity type repository.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\content_sync\Plugin\SyncNormalizerDecoratorManager $decorator_manager
   *   Plugin  decorator.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, EntityTypeRepositoryInterface $entity_type_repository, EntityFieldManagerInterface $entity_field_manager, SyncNormalizerDecoratorManager $decorator_manager) {
    parent::__construct($entity_type_manager, $entity_type_repository, $entity_field_manager);
    $this->decoratorManager = $decorator_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []): mixed {
    if (is_null($data)) {
      return NULL;
    }
    $original_data = $data;

    // Get the entity type ID while letting context override the $class param.
    $entity_type_id = !empty($context['entity_type']) ? $context['entity_type'] : $this->entityTypeRepository->getEntityTypeFromClass($class);

    $bundle = FALSE;
    // Get the entity type definition.
    $entity_type_definition = $this->entityTypeManager->getDefinition($entity_type_id, FALSE);
    if ($entity_type_definition->hasKey('bundle')) {
      $bundle_key = $entity_type_definition->getKey('bundle');
      // Get the base field definitions for this entity type.
      $base_field_definitions = $this->entityFieldManager->getBaseFieldDefinitions($entity_type_id);

      // Get the ID key from the base field definition for the bundle key or
      // default to 'value'.
      $key_id = isset($base_field_definitions[$bundle_key]) ? $base_field_definitions[$bundle_key]->getFieldStorageDefinition()
        ->getMainPropertyName() : 'value';

      // Normalize the bundle if it is not explicitly set.
      $bundle = $data[$bundle_key][0][$key_id] ?? ($data[$bundle_key] ?? NULL);
    }

    $context['bundle'] = $bundle;
    // Decorate data before denormalizing it.
    $this->decorateDenormalization($data, $entity_type_id, $format, $context);

    // Data to Entity.
    $entity = parent::denormalize($data, $class, $format, $context);

    // Decorate denormalized entity before retuning it.
    $this->decorateDenormalizedEntity($entity, $original_data, $format, $context);

    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []): array|\ArrayObject|bool|float|int|NULL|string {
    /** @var \Drupal\Core\Entity\ContentEntityInterface $object */
    $normalized_data = parent::normalize($object, $format, $context);
    $normalized_data['_content_sync'] = $this->getContentSyncMetadata($object, $context);

    // Decorate normalized entity before retuning it.
    if (is_a($object, ContentEntityInterface::class, TRUE)) {
      $this->decorateNormalization($normalized_data, $object, $format, $context);
    }
    return $normalized_data;
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, ?string $format = NULL, array $context = []): bool {
    return parent::supportsNormalization($data, $format, $context) && !empty($data->is_content_sync);
  }

  /**
   * The function returns metadata including the entity object.
   *
   * @param mixed $object
   *   Function is typically an object representing some entity in your system.
   * @param array $context
   *   Function is a protected.
   *
   * @return array
   *   The value of the entity type ID of the object passed function
   */
  protected function getContentSyncMetadata($object, $context = []) {
    $metadata = [
      'entity_type' => $object->getEntityTypeId(),
    ];
    return $metadata;
  }

  /**
   * {@inheritdoc}
   */
  protected function getDecoratorManager() {
    return $this->decoratorManager;
  }

}

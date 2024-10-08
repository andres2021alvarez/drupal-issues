<?php

namespace Drupal\content_sync\Normalizer;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\content_sync\ContentSyncManager;
use Drupal\content_sync\Plugin\SyncNormalizerDecoratorManager;

/**
 * Paragraph entity normalizer class.
 */
class ParagraphEntityNormalizer extends ContentEntityNormalizer {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = 'Drupal\paragraphs\Entity\Paragraph';

  /**
   * The entity repository.
   *
   * @var \Drupal\Core\Entity\EntityRepositoryInterface
   */
  protected $entityRepository;

  /**
   * Constructs an EntityNormalizer object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entity_type_repository
   *   The entity type repository.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param SyncNormalizerDecoratorManager $decorator_manager
   * @param EntityRepositoryInterface $entityRepository
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, EntityTypeRepositoryInterface $entity_type_repository, EntityFieldManagerInterface $entity_field_manager, SyncNormalizerDecoratorManager $decorator_manager, EntityRepositoryInterface $entityRepository) {
    parent::__construct($entity_type_manager, $entity_type_repository, $entity_field_manager, $decorator_manager);
    $this->entityRepository = $entityRepository;
  }


  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []): mixed {
    if(isset($data['parent_id']['target_uuid']) &&
       isset($data['parent_id']['target_type']) ) {
      $referenced_entity_uuid = $data['parent_id']['target_uuid'];
      $referenced_entity_type = $data['parent_id']['target_type'];
      if ($referenced_entity = $this->entityRepository->loadEntityByUuid($referenced_entity_type, $referenced_entity_uuid)) {
        $data["parent_id"]["value"] = $referenced_entity->id();
        unset($data['parent_id']['target_uuid']);
        unset($data['parent_id']['target_type']);
      }
    }
    $entity = parent::denormalize($data, $class, $format, $context);
    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []): array|\ArrayObject|bool|float|int|NULL|string {
    $normalized_data = parent::normalize($object, $format, $context);
    if ($object->getParentEntity()){
      // We are only interested in content entities.
      $entity = $object->getParentEntity();
      $reflection = new \ReflectionClass($entity);
      if ($reflection->implementsInterface(ContentEntityInterface::class)) {
        $target_type = $entity->getEntityTypeId();
        $target_uuid = $entity->uuid();
        $ids = [
          $target_type,
          $entity->bundle(),
          $target_uuid,
        ];
        $dependency = implode(ContentSyncManager::DELIMITER, $ids);
        // Add the target entity UUID and type to the normalized output values.
        $normalized_data['parent_id']['target_type'] = $target_type;
        $normalized_data['parent_id']['target_uuid'] = $target_uuid;
        // Include a dependency
        $normalized_data['_content_sync']['entity_dependencies'][$target_type][] = $dependency;
        // Remove main property - we set target_uuid
        if (!empty($normalized_data['parent_id'][0])) {
          unset($normalized_data['parent_id'][0]);
        }
      }
    }
    // Remove target revision id as we are not syncing revisions.
        if (isset($normalized_data['revision_id'])){
          unset($normalized_data['revision_id']);
          unset($normalized_data['revision_default']);
          unset($normalized_data['isDefaultRevision']);
          unset($normalized_data['revision_translation_affected']);
        }
    return $normalized_data;
  }
}

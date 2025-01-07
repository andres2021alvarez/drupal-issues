<?php

namespace Drupal\content_sync\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\content_sync\ContentSyncManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Content Export Multiple.
 *
 * @package Drupal\content_sync_ui\Form
 */
class ContentExportMultiple extends ConfirmFormBase {

  use ContentExportTrait;

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Private Temp Store Factory service.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * Interface sync manager.
   *
   * @var \Drupal\content_sync\ContentSyncManagerInterface
   */
  protected $contentSyncManager;

  /**
   * Interface sync manager ui.
   *
   * @var \Drupal\content_sync_ui\Toolbox\ContentSyncUIToolboxInterface
   */
  protected $contentSyncUIToolbox;

  /**
   * Initializes the content array.
   *
   * @var array
   */
  protected $entityList = [];

  /**
   * Formats the content array.
   *
   * @var mixed
   */
  protected $formats;

  /**
   * Constructs a ContentSyncMultiple form object.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $manager
   *   The entity type manager.
   * @param \Drupal\content_sync\ContentSyncManagerInterface $content_sync_manager
   *   The content sync manager.
   * @param string $formats
   *   The format manager.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system interface.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, EntityTypeManagerInterface $manager, ContentSyncManagerInterface $content_sync_manager, array $formats, FileSystemInterface $file_system) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->entityTypeManager = $manager;
    $this->contentSyncManager = $content_sync_manager;
    $this->formats = $formats;
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('entity_type.manager'),
      $container->get('content_sync.manager'),
      $container->getParameter('serializer.formats'),
      $container->get('file_system')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'content_sync_export_multiple_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->formatPlural(count($this->entityList), 'Are you sure you want to export this item?', 'Are you sure you want to export these items?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('system.admin_content');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Export');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->entityList = $this->tempStoreFactory->get('content_sync_ui_multiple_confirm')
      ->get($this->currentUser()
        ->id());

    if (empty($this->entityList)) {
      return new RedirectResponse($this->getCancelUrl()
        ->setAbsolute()
        ->toString());
    }

    // List of items to export.
    $items = [];
    foreach ($this->entityList as $uuid => $entity_info) {
      $storage = $this->entityTypeManager->getStorage($entity_info['entity_type']);
      $entity = $storage->load($entity_info['entity_id']);
      if (!empty($entity)) {
        $items[$uuid] = $entity->label();
      }
    }
    $form['content_list'] = [
      '#theme' => 'item_list',
      '#title' => 'Content List.',
      '#items' => $items,
    ];

    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    if ($form_state->getValue('confirm') && !empty($this->entityList)) {
      // Delete the content tar file in case an older version exist.
      $this->fileSystem->delete($this->getTempFile());

      $entities_list = [];
      foreach ($this->entityList as $entity_info) {
        $entities_list[] = [
          'entity_type' => $entity_info['entity_type'],
          'entity_id' => $entity_info['entity_id'],
        ];
      }
      if (!empty($entities_list)) {
        $serializer_context['export_type'] = 'tar';
        $serializer_context['include_files'] = 'folder';
        $batch = $this->generateExportBatch($entities_list, $serializer_context);
        batch_set($batch);
      }
    }
    else {
      $form_state->setRedirect('system.admin_content');
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getEntityTypeManager() {
    return $this->entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  protected function getContentExporter() {
    return $this->contentSyncManager->getContentExporter();
  }

  /**
   * {@inheritdoc}
   */
  protected function getExportLogger() {
    return $this->logger('content_sync');
  }

}

<?php

namespace Drupal\content_sync\Controller;

use Drupal\Core\Config\ConfigManagerInterface;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Diff\DiffFormatter;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\File\MimeType\MimeTypeGuesser;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Mime\Header\UnstructuredHeader;

/**
 * Returns responses for content module routes.
 */
class ContentController implements ContainerInjectionInterface {


  /**
   * The target storage.
   *
   * @var \Drupal\Core\Config\StorageInterface
   */
  protected $targetStorage;

  /**
   * The source storage.
   *
   * @var \Drupal\Core\Config\StorageInterface
   */
  protected $sourceStorage;

  /**
   * The content manager.
   *
   * @var \Drupal\Core\Config\ConfigManagerInterface
   */
  protected $contentManager;

  /**
   * The file download controller.
   *
   * @var \Drupal\system\FileDownloadController
   */
  protected $fileDownloadController;

  /**
   * The diff formatter.
   *
   * @var \Drupal\Core\Diff\DiffFormatter
   */
  protected $diffFormatter;

  /**
   * Mime Type file.
   *
   * @var \Drupal\Core\File\MimeType\MimeTypeGuesser
   */
  protected $mimeTypeGuesser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('content.storage'),
      $container->get('content.storage.sync'),
      $container->get('config.manager'),
      $container->get('diff.formatter'),
      $container->get('file_system'),
      $container->get('file.mime_type.guesser')
    );
  }

  /**
   * Constructs a ContentController object.
   *
   * @param \Drupal\Core\Config\StorageInterface $target_storage
   *   The target storage.
   * @param \Drupal\Core\Config\StorageInterface $source_storage
   *   The source storage.
   * @param \Drupal\Core\Config\ConfigManagerInterface $content_manager
   *   The content manager.
   * @param \Drupal\Core\Diff\DiffFormatter $diff_formatter
   *   The diff formatter.
   * @param \Drupal\system\FileSystemInterface $file_system
   *   The file download controller.
   * @param \Drupal\Core\File\MimeType\MimeTypeGuesser $mimeTypeGuesser
   *   Mime Type file.
   */
  public function __construct(StorageInterface $target_storage, StorageInterface $source_storage, ConfigManagerInterface $content_manager, DiffFormatter $diff_formatter, FileSystemInterface $file_system, MimeTypeGuesser $mimeTypeGuesser) {
    $this->targetStorage = $target_storage;
    $this->sourceStorage = $source_storage;
    $this->contentManager = $content_manager;
    $this->diffFormatter = $diff_formatter;
    $this->fileSystem = $file_system;
    $this->mimeTypeGuesser = $mimeTypeGuesser;
  }

  /**
   * Downloads a tarball of the site content.
   */
  public function downloadExport() {
    $filename = 'content.tar.gz';
    $file_path = $this->fileSystem->getTempDirectory() . '/' . $filename;
    if (file_exists($file_path)) {
      unset($_SESSION['content_tar_download_file']);
      $mime = $this->mimeTypeGuesser->guessMimeType($file_path);
      $headers = [
        'Content-Type' => $mime . '; name="' . (new UnstructuredHeader('Content-Type', basename($file_path)))->getBodyAsString() . '"',
        'Content-Length' => filesize($file_path),
        'Content-Disposition' => 'attachment; filename="' . (new UnstructuredHeader('Content-Disposition', basename($filename)))->getBodyAsString() . '"',
        'Cache-Control' => 'private',
      ];
      return new BinaryFileResponse($file_path, 200, $headers);
    }
    return -1;
  }

  /**
   * Shows diff of specified content file.
   *
   * @param string $source_name
   *   The name of the content file.
   * @param string $target_name
   *   (optional) The name of the target content file if different from
   *   the $source_name.
   * @param string $collection
   *   (optional) The content collection name. Defaults to the default
   *   collection.
   *
   * @return string
   *   Table showing a two-way diff between the active and staged content.
   */
  public function diff($source_name, $target_name = NULL, $collection = NULL) {
    if (!isset($collection)) {
      $collection = StorageInterface::DEFAULT_COLLECTION;
    }
    $diff = $this->contentManager->diff($this->targetStorage, $this->sourceStorage, $source_name, $target_name, $collection);
    $this->diffFormatter->show_header = FALSE;

    $build = [];

    $build['#title'] = $this->t('View changes of @content_file', ['@content_file' => $source_name]);
    // Add the CSS for the inline diff.
    $build['#attached']['library'][] = 'system/diff';

    $build['diff'] = [
      '#type' => 'table',
      '#attributes' => [
        'class' => ['diff'],
      ],
      '#header' => [
        [
          'data' => $this->t('Active'),
          'colspan' => '2',
        ],
        [
          'data' => $this->t('Staged'),
          'colspan' => '2',
        ],
      ],
      '#rows' => $this->diffFormatter->format($diff),
    ];

    $build['back'] = [
      '#type' => 'link',
      '#attributes' => [
        'class' => [
          'dialog-cancel',
        ],
      ],
      '#title' => "Back to 'Synchronize content' page.",
      '#url' => Url::fromRoute('content.sync'),
    ];

    return $build;
  }

}

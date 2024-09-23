<?php

namespace Drupal\pathauto;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\path\Plugin\Field\FieldWidget\PathWidget;

/**
 * Extends the core path widget.
 */
class PathautoWidget extends PathWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $entity = $items->getEntity();

    $pattern = \Drupal::service('pathauto.generator')->getPatternByEntity($entity);
    if (empty($pattern)) {
      // Explicitly turn off pathauto here.
      $element['pathauto'] = [
        '#type' => 'value',
        '#value' => PathautoState::SKIP,
      ];
      return $element;
    }

    if (\Drupal::currentUser()->hasPermission('administer pathauto')) {
      $description = $this->t('Uncheck this to create a custom alias below. <a href="@admin_link">Configure URL alias patterns.</a>', ['@admin_link' => Url::fromRoute('entity.pathauto_pattern.collection')->toString()]);
    }
    else {
      $description = $this->t('Uncheck this to create a custom alias below.');
    }

    $element['pathauto'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Generate automatic URL alias'),
      '#default_value' => $entity->path->pathauto,
      '#description' => $description,
      '#weight' => -1,
    ];

    // Add JavaScript that will disable the path textfield when the automatic
    // alias checkbox is checked.
    $element['alias']['#states']['disabled']['input[name="path[' . $delta . '][pathauto]"]'] = ['checked' => TRUE];

    // Override path.module's vertical tabs summary.
    $element['alias']['#attached']['library'] = ['pathauto/widget'];

    return $element;
  }

}

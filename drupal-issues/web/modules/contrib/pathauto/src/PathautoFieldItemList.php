<?php

namespace Drupal\pathauto;

use Drupal\path\Plugin\Field\FieldType\PathFieldItemList;

/**
 * Delegating calls and computing values.
 */
class PathautoFieldItemList extends PathFieldItemList {

  /**
   * Delegates a method call to a list of items.
   *
   * @param string $method
   *   The method to call on each item in the list.
   *
   * @return array
   *   An array of results, where each result corresponds to the output of
   *   the method call on each item.
   *
   * @throws \Exception
   *   Throws an exception if the method call fails.
   *
   * @todo Workaround until this is fixed, see
   *   https://www.drupal.org/project/drupal/issues/2946289.
   */
  protected function delegateMethod($method) {
    // @todo Workaround until this is fixed, see
    //   https://www.drupal.org/project/drupal/issues/2946289.
    $this->ensureComputedValue();

    // Duplicate the logic instead of calling the parent due to the dynamic
    // arguments.
    $result = [];
    $args = array_slice(func_get_args(), 1);
    foreach ($this->list as $delta => $item) {
      // call_user_func_array() is way slower than a direct call so we avoid
      // using it if have no parameters.
      $result[$delta] = $args ? call_user_func_array([$item, $method], $args) : $item->{$method}();
    }
    return $result;
  }

  /**
   * Computes and updates the value for the path alias.
   */
  protected function computeValue() {
    parent::computeValue();

    // For a new entity, default to creating a new alias.
    if ($this->getEntity()->isNew()) {
      $this->list[0]->set('pathauto', PathautoState::CREATE);
    }
  }

}

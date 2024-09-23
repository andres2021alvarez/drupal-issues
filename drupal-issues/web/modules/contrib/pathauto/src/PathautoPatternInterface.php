<?php

namespace Drupal\pathauto;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Pathauto pattern entities.
 */
interface PathautoPatternInterface extends ConfigEntityInterface {

  /**
   * Get the tokenized pattern used during alias generation.
   *
   * @return string
   *   The tokenized pattern.
   */
  public function getPattern();

  /**
   * Sets the tokenized pattern for alias generation.
   *
   * @param string $pattern
   *   The tokenized pattern to set.
   *
   * @return $this
   *   The current instance for chaining.
   */
  public function setPattern($pattern);

  /**
   * Gets the type of this pattern.
   *
   * @return string
   *   The type of the pattern.
   */
  public function getType();

  /**
   * Retrieves the alias type associated with this instance.
   *
   * @return \Drupal\pathauto\AliasTypeInterface
   *   The alias type.
   */
  public function getAliasType();

  /**
   * Gets the weight of this pattern relative to other patterns of this type.
   *
   * @return int
   *   The weight value.
   */
  public function getWeight();

  /**
   * Sets the weight of this pattern relative to other patterns the same type.
   *
   * @param int $weight
   *   The weight to assign to this pattern.
   *
   * @return $this
   *   The current instance for method chaining.
   */
  public function setWeight($weight);

  /**
   * Returns the contexts associated with this pattern.
   *
   * @return \Drupal\Core\Plugin\Context\ContextInterface[]
   *   An array of context objects.
   */
  public function getContexts();

  /**
   * Returns whether a relationship exists.
   *
   * @param string $token
   *   Relationship identifier.
   *
   * @return bool
   *   TRUE if the relationship exists, FALSE otherwise.
   */
  public function hasRelationship($token);

  /**
   * Adds a relationship.
   *
   * The relationship will not be changed if it already exists.
   *
   * @param string $token
   *   Relationship identifier.
   * @param string|null $label
   *   (optional) A label, will use the label of the referenced context if not
   *   provided.
   *
   * @return $this
   */
  public function addRelationship($token, $label = NULL);

  /**
   * Replaces a relationship.
   *
   * Only already existing relationships are updated.
   *
   * @param string $token
   *   Relationship identifier.
   * @param string|null $label
   *   (optional) A label, will use the label of the referenced context if not
   *   provided.
   *
   * @return $this
   */
  public function replaceRelationship($token, $label);

  /**
   * Removes a relationship.
   *
   * @param string $token
   *   Relationship identifier.
   *
   * @return $this
   */
  public function removeRelationship($token);

  /**
   * Returns a list of relationships.
   *
   * @return array[]
   *   Keys are context tokens, and values are arrays with the following keys:
   *   - label (string|null, optional): The human-readable label of this
   *     relationship.
   */
  public function getRelationships();

  /**
   * Gets the collection of selection conditions.
   *
   * @return \Drupal\Core\Condition\ConditionInterface[]|\Drupal\Core\Condition\ConditionPluginCollection
   *   An array of condition interfaces or a condition plugin collection.
   */
  public function getSelectionConditions();

  /**
   * Adds selection criteria.
   *
   * @param array $configuration
   *   Configuration of the selection criteria.
   *
   * @return string
   *   The condition id of the new criteria.
   */
  public function addSelectionCondition(array $configuration);

  /**
   * Gets the selection criteria for a given condition ID.
   *
   * @param string $condition_id
   *   The ID of the condition to retrieve.
   *
   * @return \Drupal\Core\Condition\ConditionInterface
   *   The condition interface for the specified ID.
   */
  public function getSelectionCondition($condition_id);

  /**
   * Removes selection criteria by condition id.
   *
   * @param string $condition_id
   *   The id of the condition.
   *
   * @return $this
   */
  public function removeSelectionCondition($condition_id);

  /**
   * Gets the selection logic used by the criteria (ie. "and" or "or").
   *
   * @return string
   *   Either "and" or "or"; represents how the selection criteria are combined.
   */
  public function getSelectionLogic();

  /**
   * Determines if this pattern can be applied to the given object.
   *
   * @param mixed $object
   *   The object to check for applicability.
   *
   * @return bool
   *   TRUE if the pattern can be applied, FALSE otherwise.
   */
  public function applies($object);

}

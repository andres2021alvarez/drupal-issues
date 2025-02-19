<?php

namespace Drupal\webform_rest\Event;

use Drupal\Component\EventDispatcher\Event;

/**
 * Class WebformSubmitReturnEvent, an event to change the return.
 *
 * @package Drupal\webform_rest\Event
 */
class WebformSubmitReturnEvent extends Event {
  const WEBFORM_SUBMIT_RETURN = 'webform_rest.submit.return';

  /**
   * Stores the return type, success or error.
   *
   * @var string
   */
  private $type;

  /**
   * Stores the submission values.
   *
   * @var array
   */
  protected $submissionValues;

  /**
   * Stores the response from the request.
   *
   * @var array
   */
  protected $returnData;

  /**
   * Stores the HTTP code from the request.
   *
   * @var int
   */
  protected $httpCode;

  /**
   * Construct for injection dependency.
   *
   * @param string $type
   *   The return type, success or error.
   * @param array $submissionValues
   *   The submission values.
   * @param array $returnData
   *   The response from the request.
   * @param int $httpCode
   *   The HTTP code from the request.
   */
  public function __construct(string $type, array $submissionValues, array &$returnData, int &$httpCode) {
    $this->type = $type;
    $this->submissionValues = $submissionValues;
    $this->returnData = &$returnData;
    $this->httpCode = &$httpCode;
  }

  /**
   * Get return type, success or error.
   */
  public function getType(): string {
    return $this->type;
  }

  /**
   * Get submit values.
   */
  public function getSubmissionValues(): array {
    return $this->submissionValues;
  }

  /**
   * Get response from request.
   */
  public function &getReturnData(): array {
    return $this->returnData;
  }

  /**
   * Get response from request.
   */
  public function &getHttpCode(): int {
    return $this->httpCode;
  }

}

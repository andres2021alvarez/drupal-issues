<?php

namespace Drupal\content_sync\Encoder;

use Drupal\Component\Serialization\Yaml;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

/**
 * Yaml Encoder.
 *
 * @package Drupal\yaml_serialization
 */
class YamlEncoder implements EncoderInterface, DecoderInterface {

  /**
   * The formats that this Encoder supports.
   *
   * @var string
   */
  protected $format = 'yaml';

  /**
   * The encoding type that this Encoder supports.
   *
   * @var mixed
   */
  protected $yaml;

  /**
   * Constructor.
   */
  public function __construct(Yaml $yaml) {
    $this->yaml = $yaml;
  }

  /**
   * Decode a YAML string.
   */
  public function decode($data, $format, array $context = []) {
    return $this->yaml->decode($data);
  }

  /**
   * Supports array syntax for arrays.
   */
  public function supportsDecoding($format) {
    return $format == $this->format;
  }

  /**
   * Encode array using yaml encoding.
   */
  public function encode(mixed $data, string $format, array $context = []): string {
    return $this->yaml->encode($data);
  }

  /**
   * Supports encoding with string encoding.
   */
  public function supportsEncoding(string $format): bool {
    return $format == $this->format;
  }

}

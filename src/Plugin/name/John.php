<?php

/**
 * @file
 * Contains Drupal\plug_example\Plugin\name\John
 */

namespace Drupal\plug_example\Plugin\name;

/**
 * Class John
 * @package Drupal\plug_example\Plugin\name
 *
 * @Name(
 *   id = "john",
 *   company = TRUE
 * )
 */
class John extends NameBase implements NameInterface {
  protected $name = 'John Doe';
}

<?php

/**
 * @file
 * Contains Drupal\plug_example\Plugin\name\Acme
 */

namespace Drupal\plug_example\Plugin\name;

/**
 * Class Acme
 * @package Drupal\plug_example\Plugin\name
 *
 * @Name(
 *   id = "acme",
 *   company = TRUE
 * )
 */
class Acme extends NameBase implements NameInterface {
  protected $name = 'Acme';
}

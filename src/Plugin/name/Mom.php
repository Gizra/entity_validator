<?php

/**
 * @file
 * Contains Drupal\plug_example\Plugin\name\Mom
 */

namespace Drupal\plug_example\Plugin\name;

/**
 * Class Mom
 * @package Drupal\plug_example\Plugins
 *
 * @Name(
 *   id = "mom",
 *   company = FALSE
 * )
 */
class Mom extends NameBase implements NameInterface {

  protected $name = 'Mom';

}

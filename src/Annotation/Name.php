<?php

/**
 * @file
 * Contains \Drupal\plug_example\Annotation\Name.
 */

namespace Drupal\plug_example\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Name annotation object.
 *
 * @ingroup plug_example_api
 *
 * @Annotation
 */
class Name extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The type of the name.
   *
   * @var bool
   */
  public $company;

}

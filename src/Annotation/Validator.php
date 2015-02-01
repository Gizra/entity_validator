<?php

/**
 * @file
 * Contains \Drupal\plug_example\Annotation\Name.
 */

namespace Drupal\entity_validator\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Name annotation object.
 *
 * @ingroup entity_validator
 *
 * @Annotation
 */
class Validator extends Plugin {

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
  public $label;

  /**
   * @var string
   *
   * Description for the plugin.
   */
  public $description;

  /**
   * @var string
   *
   * The entity type.
   */
  public $entity_type;

  /**
   * @var string
   *
   * The entity bundle.
   */
  public $bundle;

  /**
   * @var bool
   *
   * Determine if the handler can handle entity.
   */
  public $entity = TRUE;
}

<?php
/**
 * @file
 * The annotation definition for the entity validator plugin.
 */
namespace Drupal\entity_validator\Annotation;

use Drupal\Component\Annotation\Plugin;

class EntityValidator extends Plugin {

  /**
   * @var String
   *   The ID of the plugin.
   */
  public $id;

  /**
   * @var String
   *   The label of the plugin.
   */
  public $label;

  /**
   * @var String
   *   The description of the plugin.
   */
  public $description;

  /**
   * @var String
   *   The entity type.
   */
  public $entity_type;

  /**
   * @var String
   *   The entity bundle.
   */
  public $bundle;

}

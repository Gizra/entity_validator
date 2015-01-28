<?php
/**
 * @file
 * Contains \Drupal\plug_example\ValidatorPluginManager.
 */

namespace Drupal\entity_validator;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\plug\Util\Module;

/**
 * Entity validator plugin manager.
 */
class ValidatorPluginManager extends DefaultPluginManager {

  /**
   * Constructs ValidatorPluginManager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \DrupalCacheInterface $cache_backend
   *   Cache backend instance to use.
   */
  public function __construct(\Traversable $namespaces, \DrupalCacheInterface $cache_backend) {
    parent::__construct('Plugin/validator', $namespaces, 'Drupal\entity_validator\Interfaces\ValidateInterface', '\Drupal\entity_validator\Annotation\Validator');
    $this->setCacheBackend($cache_backend, 'validator_plugins');
    $this->alterInfo('validator_plugin');
  }

  /**
   * NamePluginManager factory method.
   *
   * @param string $bin
   *   The cache bin for the plugin manager.
   *
   * @return ValidatorPluginManager
   *   The created manager.
   */
  public static function create($bin = 'cache') {
    return new static(Module::getNamespaces(), _cache_get_object($bin));
  }

  /**
   * Return the validation handler based on entity type and bundle.
   *
   * @param $entity_type
   *   The entity type.
   * @param $bundle
   *   The bundle name.
   *
   * @return \Drupal\entity_validator\Base\EntityValidateBase|NULL
   *   The handler object if found, or NULL.
   */
  public static function EntityValidator($entity_type, $bundle) {
    $plugin = static::create();
    return $plugin->createInstance($entity_type . '_' . $bundle);
  }

  /**
   * Return the validation handler for schema validator.
   *
   * @param $schema
   *   The name of the validator.
   *
   * @return \Drupal\entity_validator\Base\ObjectValidateBase|NULL
   *   The validator object.
   */
  public static function SchemaValidator($schema) {
    $plugin = static::create();
    return $plugin->createInstance($schema);
  }

}

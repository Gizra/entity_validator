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
    parent::__construct('Plugin/validator', $namespaces, 'Drupal\entity_validator\Interfaces');
    $this->setCacheBackend($cache_backend, 'name_plugins');
    $this->alterInfo('name_plugin');
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

}

<?php

/**
 * @file
 * Contains \Drupal\plug_example\NamePluginManager.
 */

namespace Drupal\entity_validator;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\plug\Util\Module;

/**
 * Name plugin manager.
 */
class ValidatorPluginManager extends DefaultPluginManager {

  /**
   * Constructs NamePluginManager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \DrupalCacheInterface $cache_backend
   *   Cache backend instance to use.
   */
  public function __construct(\Traversable $namespaces, \DrupalCacheInterface $cache_backend) {
    parent::__construct('Plugin/name', $namespaces, 'Drupal\plug_example\Plugin\name\NameInterface', '\Drupal\plug_example\Annotation\Name');
    $this->setCacheBackend($cache_backend, 'name_plugins');
    $this->alterInfo('name_plugin');
  }

  /**
   * NamePluginManager factory method.
   *
   * @param string $bin
   *   The cache bin for the plugin manager.
   *
   * @return NamePluginManager
   *   The created manager.
   */
  public static function create($bin = 'cache') {
    return new static(Module::getNamespaces(), _cache_get_object($bin));
  }

}

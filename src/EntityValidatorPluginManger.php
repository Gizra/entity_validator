<?php

/**
 * @file
 * Contains \Drupal\entity_validator\EntityValidatorPluginManager.
 */

namespace Drupal\entity_validator;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manages entity validator plugins.
 */
class EntityValidatorPluginManger extends DefaultPluginManager {

  /**
   * Constructs entity validator plugin manager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/validator', $namespaces, $module_handler, 'Drupal\entity_validator\Annotation\EntityValidator');
    $this->alterInfo('entity_validator_alter');
    $this->setCacheBackend($cache_backend, 'entity_validator');
  }
}

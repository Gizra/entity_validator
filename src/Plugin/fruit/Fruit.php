<?php

/**
 * @file
 * Contains Drupal\plug_example\Plugin\fruit\Fruit
 */

namespace Drupal\plug_example\Plugin\fruit;

use Drupal\Component\Plugin\PluginBase;

class Fruit extends PluginBase implements FruitInterface {

  /**
   * {@inheritdoc}
   */
  public function display() {
    $definition = $this->getPluginDefinition();
    if (!empty($definition['slimy'])) {
      return t('Yikes, %name!', array('%name' => $definition['label']));
    }
    return t('Fruit name: %name', array('%name' => $definition['label']));
  }

}

<?php

/**
 * @file
 * Contains Drupal\plug_example\Plugin\name\NameBase
 */

namespace Drupal\plug_example\Plugin\name;

use Drupal\Component\Plugin\PluginBase;

abstract class NameBase extends PluginBase implements NameInterface {

  /**
   * The name.
   *
   * @var string
   */
  protected $name;

  /**
   * {@inheritdoc}
   */
  public function displayName() {
    $definition = $this->getPluginDefinition();
    $replacement = $this->configuration['em'] ? '%name' : '@name';
    if ($definition['company']) {

      return t('Company name: ' . $replacement . ' Inc.', array(
        $replacement => $this->name,
      ));
    }
    return t('My name is: ' . $replacement, array(
      $replacement => $this->name,
    ));
  }

}

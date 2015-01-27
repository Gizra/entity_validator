<?php

/**
 * @file
 * Contains Drupal\plug_example\Plugin\fruit\FruitInterface
 */

namespace Drupal\plug_example\Plugin\fruit;

interface FruitInterface {

  /**
   * Displays a fruit.
   *
   * @return string
   *   The fruit representation.
   */
  public function display();

}

<?php

/**
 * Validate the node object.
 */
class NodeValidate extends AbstractEntityValidate {

  public function __construct() {
    $this->entityType = 'node';
  }
}

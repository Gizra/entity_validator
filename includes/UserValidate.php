<?php

/**
 * User validation.
 *
 * @todo: Check if we need to code the validation.
 */
class UserValidate extends AbstractEntityValidate {

  public function __construct() {
    $this->entityType = 'user';
  }
}

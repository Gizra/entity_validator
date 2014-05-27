<?php

/**
 * Comment validation.
 *
 * @todo: Check if we need to code the validation.
 */
class CommentValidate extends AbstractEntityValidate {

  public function __construct() {
    $this->entityType = 'comment';
  }
}

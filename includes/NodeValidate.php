<?php

/**
 * Class NodeValidate
 *
 * Although the module used for node validation the parent class,
 * AbstractValidate, can be used for any entity type.
 */
class NodeValidate extends AbstractValidate {

  public function __construct() {
    $this->entityType = 'node';
    $this->errorLevel = 0;
  }

  /**
   * Overriding. The node entity must have a label!.
   */
  public function validate() {
    if (!in_array('title', array_keys($this->fields))) {
      $this->setError(t('The title is missing'));
    }

    parent::validate();
  }
}

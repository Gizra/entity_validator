<?php

class NodeValidate extends AbstractValidate {

  public function __construct() {
    $this->entityType = 'node';
    $this->errorLevel = 0;
  }

  /**
   * Overriding. The node entity must have a label!.
   */
  public function validate() {
    if (empty($this->label)) {
      $this->setError(t('The title is missing'));
    }

    parent::validate();
  }
}

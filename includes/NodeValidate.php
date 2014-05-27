<?php

/**
 * Validate the node object.
 */
class NodeValidate extends AbstractEntityValidate {

  public function __construct() {
    $this->entityType = 'node';
  }

  /**
   * Overriding. The node entity must have a label!.
   */
  public function validate() {
    if (empty($this->fields['title'])) {
      $this->setError(t('The title is missing'));
    }

    parent::validate();
  }
}

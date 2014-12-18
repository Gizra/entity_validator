<?php

/**
 * The class designed to set public fields more easily. similar to Drupal 8
 * BaseFieldDefinition.
 */
class FieldsInfo {

  protected $validator;
  protected $definition = array();

  static public function setFields(&$public_field = array()) {
    return new static($public_field);
  }

  public function __construct(&$public_field = array()) {
    $this->definition =& $public_field;
  }

  public function setValidator($validator) {
    $this->validator = $validator;
    return $this;
  }

  public function setRequired($status = TRUE) {
    $this->definition['required'] = $status;
    return $this;
  }

  public function setProperty($property) {
    $this->definition['property'] = $property;
    return $this;
  }

  public function setSubProperty($sub_property) {
    $this->definition['sub_property'] = $sub_property;
    return $this;
  }

  public function addCallback($callback, $object = NULL) {
    if (!$object) {
      $object = $this->validator;
    }

    $this->definition['validators'][] = array($object, $callback);
    return $this;
  }

  public function setCallbacks($callbacks = array()) {
    foreach ($callbacks as $callback) {
      $this->addCallback($callback);
    }
    return $this;
  }
}
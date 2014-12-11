<?php

class ObjectValidateBase implements ObjectValidateInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin) {
    // TODO: Implement __construct() method.
  }

  /**
   * {@inheritdoc}
   */
  public function loadSchema($name) {
    // TODO: Implement loadSchema() method.
  }

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    // TODO: Implement publicFieldsInfo() method.
  }

  /**
   * {@inheritdoc}
   */
  public function getPublicFields() {
    // TODO: Implement getPublicFields() method.
  }

  /**
   * {@inheritdoc}
   */
  public function validate($object, $silent = FALSE) {
    // TODO: Implement validate() method.
  }

  /**
   * {@inheritdoc}
   */
  public function setError($property_name, $message, $params = '') {
    // TODO: Implement setError() method.
  }

  /**
   * {@inheritdoc}
   */
  public function getErrors($squash = TRUE) {
    // TODO: Implement getErrors() method.
  }

  /**
   * {@inheritdoc}
   */
  public function clearErrors() {
    // TODO: Implement clearErrors() method.
  }
}

<?php

class ObjectValidateBase implements ObjectValidateInterface {

  /**
   * @var Array
   * The schema information.
   */
  protected $schema;

  /**
   * @var Array
   * The plugin information.
   */
  protected $plugin;

  /**
   * @param Array $schema
   *   The schema information.
   *
   * @return $this
   */
  public function setSchema($schema) {
    $this->schema = $schema;
    return $this;
  }

  /**
   * @return Array
   *   Return the schema information.
   */
  public function getSchema() {
    return $this->schema;
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin) {
    $this->plugin = $plugin;

    if (empty($this->plugin['schema'])) {
      throw new \EntityValidatorException('Missing schema name.');
    }

    $schema = drupal_get_schema($plugin['schema'], TRUE);
    if (!$schema || !db_table_exists($plugin['schema'])) {
      throw new \EntityValidatorException(format_string('A schema or a definition for @name was not found.', array('@name' => $plugin['schema'])));
    }
    $this->setSchema($schema);
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

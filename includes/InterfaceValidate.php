<?php

interface Validate {

  /**
   * Set the bundle of the node.
   *
   * @param $bundle
   *  The bundle machine name.
   *
   * @return $this
   */
  public function setBundle($bundle);

  /**
   * Retrieve the bundle.
   */
  public function getBundle();

  /**
   * Set the entity type.
   *
   * @param $entity
   *  The name of the entity.
   *
   * @return $this
   */
  public function setEntity($entity);

  /**
   * Adding the field the validation process.
   *
   * @param $name
   *  The machine name of the field.
   * @param $value
   *  The value of the field.
   *
   * @return $this
   */
  public function addField($name, $value);

  /**
   * Retrieve fields.
   *
   * @return Array
   */
  public function getFields();

  /**
   * Set the fields.
   *
   * @param $fields
   *  The desire structure.
   *
   * @return $this.
   */
  public function setFields($fields);

  /**
   * Set the error level.
   */
  public function setErrorLevel($level);

  /**
   * Retrieve the errors.
   *
   * @return Array
   */
  public function getErrors();

  /**
   * Add metadata about the object.
   *
   * @param $key
   *  The key.
   * @param $value
   *  The value.
   * @return $this
   */
  public function addMetaData($key, $value);

  /**
   * Retrieve all the metadata.
   *
   * @return Array
   *  All the metadata the user added to the object.
   */
  public function getMetaData();

  /**
   * Register pre validation function in order to manipulate the object.
   * Register function for the pre-validate process used when i want to validate
   * the current validator instance and not validate each field using
   * node_validator_callback.
   *
   * @param $function
   *  The name of the functions run before validating fields.
   *
   * @return $this
   */
  public function preValidateRegister($function);

  /**
   * Initialize the validate process.
   */
  public function validate();

  /**
   * Set error.
   *
   * @param $message
   *  Set the error message.
   *
   * @throws Exception
   *  When setting the error level to 1 exception will be thrown with the value
   *  of the error.
   */
  public function setError($message);
}

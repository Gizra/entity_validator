<?php

interface EntityValidateInterface {

  /**
   * Constructor for the Validator handler.
   *
   * @param $plugin
   *   The validator plugin object.
   */
  public function __construct($plugin);

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
  public function setEntityType($entity);

  /**
   * Return the entity type.
   *
   * @return String
   */
  public function getEntityType();

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
   * Set field validate and preprocess methods.
   *
   * @return Array.
   */
  public function getFieldsInfo();

  /**
   * Initialize the validate process.
   *
   * @param $entity
   *  The entity we need to validate.
   * @param $silent
   *  Determine if we throw the exception or return array with the errors.
   * 
   * @throws EntityValidatorException
   */
  public function validate($entity, $silent = FALSE);

  /**
   * Preprocess the field. This is useful when we need to alter a field before
   * the validation process.
   *
   * @param $field_name
   *  The field machine name.
   * @param $callbacks
   *  List of callbacks.
   * @param EntityMetadataWrapper $wrapper
   *  The entity wrapped with the entity metadata wrapper.
   *  @see entity_metadata_wrapper().
   * @param $state
   *  Define if we need to set the value or validate the field. Allowed values:
   *  preprocess, validate. Default is preprocess.
   */
  public function iterateFields($field_name, $callbacks, EntityMetadataWrapper $wrapper, $state = 'preprocess');

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

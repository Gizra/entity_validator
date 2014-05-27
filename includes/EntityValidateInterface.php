<?php

interface EntityValidateInterface {

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

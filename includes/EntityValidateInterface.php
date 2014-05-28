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
   * Set field validation and morphers.
   *
   * @return Array.
   */
  public function fieldsMetaData();

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

  /**
   * Verify the field is not empty.
   *
   * @param $value
   *  The value of the field.
   * @param $field
   *  The field name.
   *
   * @return boolean
   */
  public function isNotEmpty($value, $field);

  /**
   * Check if the field is a text field.
   *
   * @param $value
   *  The value of the field.
   * @param $field
   *  The field name.
   *
   * @return boolean
   */
  public function isText($value, $field);

  /**
   * Check if the field is numeric field.
   *
   * @param $value
   *  The value of the field.
   * @param $field
   *  The field name.
   *
   * @return boolean
   */
  public function isNumeric($value, $field);

  /**
   * Verify the field is a list AKA array.
   *
   * @param $value
   *  The value of the field.
   * @param $field
   *  The field name.
   *
   * @return boolean
   */
  public function isList($value, $field);

  /**
   * Verify if the field present only a year.
   *
   * @param $value
   *  The value of the field.
   * @param $field
   *  The field name.
   *
   * @return boolean
   */
  public function isYear($value, $field);

  /**
   * Verify the given integer is a unix timestamp format integer.
   *
   * @param $value
   *  The value of the field.
   * @param $field
   *  The field name.
   *
   * @return boolean
   */
  public function isUnixTimeStamp($value, $field);

  /**
   * Change the given value to a date format.
   *
   * @param $value
   *  The value we need to change.
   *
   * @return mixed
   */
  public function morphDate($value);

  /**
   * Wrap the value to a text format value.
   *
   * @param $value
   *  The value we need to change.
   *
   * @return mixed
   */
  public function morphText($value);

  /**
   * Change the given value from a single value to a multiple value.
   *
   * @param $value
   *  The value we need to change.
   *
   * @return mixed
   */
  public function morphList($value);

  /**
   * Apply array_unique on the given value.
   *
   * @param $value
   *  The value we need to change.
   *
   * @return mixed
   */
  public function morphUnique($value);
}

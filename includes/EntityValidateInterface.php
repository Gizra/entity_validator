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
   * Set the field validate and preprocess methods.
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
   *  Defaults to FALSE.
   *
   * @throws EntityValidatorException
   */
  public function validate($entity, $silent = FALSE);

  /**
   * Set error.
   *
   * @param $field_name
   *  The name of the field.
   * @param $message
   *  Set the error message without wrapping the text with t().
   * @param $params
   *  Optional. The parameters for the t() function.
   *
   * @throws Exception
   *  When setting the error level to 1 exception will be thrown with the value
   *  of the error.
   *
   * @code
   *  $params = array(
   *    '@value' => 'foo',
   *    '@field' => 'date',
   *  );
   *  $this->setError('field_date', 'The value @value is invalid for the field @field', $params);
   *  $this->setError('title', 'The node must have a title');
   * @endcode
   */
  public function setError($field_name, $message, $params = '');

  /**
   * Retrieve the errors.
   *
   * @param $squash
   *   If TRUE, the message and params would be squashed to a single message. If
   *   FALSE it will keep the "message" and "params" separated. Defaults to
   *   TRUE.
   *
   * @return Array
   *  Return the errors which occurred during the validation process.
   */
  public function getErrors($squash = TRUE);

  /**
   * Clear the errors array.
   */
  public function clearErrors();
}

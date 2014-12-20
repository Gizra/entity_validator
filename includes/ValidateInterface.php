<?php

interface ValidateInterface {

  /**
   * Constructor for the Validator handler.
   *
   * @param $plugin
   *   The validator plugin object.
   */
  public function __construct($plugin);

  /**
   * Return array with the field validate methods.
   *
   * @return array
   */
  public function publicFieldsInfo();

  /**
   * Return the processed array with the field validation declarations.
   *
   * @return array
   */
  public function getPublicFields();

  /**
   * Initialize the validate process.
   *
   * @param $object
   *   The object we need to validate.
   * @param $silent
   *   Determine if we throw the exception or return array with the errors.
   *   Defaults to FALSE.
   *
   * @throws EntityValidatorException
   */
  public function validate($object, $silent = FALSE);

  /**
   * Set error.
   *
   * @param $property
   *   The name of the property.
   * @param $message
   *   Set the error message without wrapping the text with t().
   * @param $params
   *   Optional. The parameters for the t() function.
   *
   * @throws Exception
   *   When setting the error level to 1 exception will be thrown with the value
   *   of the error.
   *
   * @code
   *   $params = array(
   *     '@value' => 'foo',
   *     '@field' => 'date',
   *   );
   *   $this->setError('title', 'The value @value is invalid for the property @field', $params);
   *   $this->setError('uid', 'The uid must be integer');
   * @endcode
   */
  public function setError($property, $message, $params = '');

  /**
   * Retrieve the errors.
   *
   * @param $squash
   *   If TRUE, the message and params would be squashed to a single message. If
   *   FALSE it will keep the "message" and "params" separated. Defaults to
   *   TRUE.
   *
   * @return Array
   *   Return the errors which occurred during the validation process.
   */
  public function getErrors($squash = TRUE);

  /**
   * Clear the errors array.
   */
  public function clearErrors();
}

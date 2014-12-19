<?php

/**
 * The class designed to set public fields more easily. similar to Drupal 8
 * BaseFieldDefinition.
 *
 * @code
 *  FieldsInfo::setFieldInfo($public_fields['body'])
 *    ->setSubProperty('body')
 *    ->setRequired()
 *    ->setValidator($this)
 *    ->addCallback('handler1');
 *    ->addCallback('handler2');
 * @endcode
 */
class FieldsInfo {

  /**
   * @var \EntityValidateInterface
   *
   * Validate handler for fields.
   */
  protected $validator;

  /**
   * @var array
   *
   * Definition of the field info.
   */
  protected $definition = array();

  /**
   * @param array $public_field
   *   The field definition set the publicFieldsInfo().
   * @param EntityValidateInterface $validator
   *   Optional. The validator object.
   * @return FieldsInfo
   */
  static public function setFieldInfo(&$public_field = array(), \EntityValidateInterface $validator = NULL) {
    return new static($public_field, $validator);
  }

  /**
   * Constructing the object.
   *
   * @param array $public_field
   *   The field definition set the publicFieldsInfo().
   * @param EntityValidateInterface $validator
   *   Optional. The validator object.
   */
  public function __construct(&$public_field = array(), \EntityValidateInterface $validator = NULL) {
    $this->definition =& $public_field;

    if ($validator) {
      $this->setValidator($validator);
    }
  }

  /**
   * Setter callback of the validator property.
   *
   * @param \EntityValidateInterface $validator
   *   The validator instance.
   *
   * @return $this
   */
  public function setValidator(\EntityValidateInterface $validator) {
    $this->validator = $validator;
    return $this;
  }

  /**
   * Set the field as required.
   *
   * @param bool $status
   *   Whether the field is required or not.
   * @return $this
   */
  public function setRequired($status = TRUE) {
    $this->definition['required'] = $status;
    return $this;
  }

  /**
   * Set the property for the entity metadata wrapper.
   *
   * @param $property
   *   The name of the property.
   *
   * @return $this
   */
  public function setProperty($property) {
    $this->definition['property'] = $property;
    return $this;
  }

  /**
   * Setting a sub property for the entity metadata wrapper.
   *
   * @param $sub_property
   *   The name of the sub property. i.e: body field accept value, safe_value,
   *   text_format. Date field accept value, value2.
   *
   * @return $this
   */
  public function setSubProperty($sub_property) {
    $this->definition['sub_property'] = $sub_property;
    return $this;
  }

  /**
   * Adding a validate callback.
   *
   * @param $callback
   *   name of the method.
   * @param \EntityValidateInterface $object
   *   The validator instance. Optional. When empty the validator property will
   *   be used.
   *
   * @return $this
   */
  public function addCallback($callback, \EntityValidateInterface $object = NULL) {
    if (!$object) {
      $object = $this->validator;
    }

    $this->definition['validators'][] = array($object, $callback);
    return $this;
  }

}
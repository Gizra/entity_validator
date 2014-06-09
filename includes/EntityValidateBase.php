<?php

/**
 * Abstract entity validation.
 */
abstract class EntityValidateBase implements EntityValidateInterface {

  /**
   * The entity type.
   *
   * @var string
   */
  protected $entityType;

  /**
   * The bundle of the node.
   *
   * @var String.
   */
  protected $bundle;

  /**
   * List of fields keyed by machine name and valued with the field's value.
   *
   * @var Array.
   */
  protected $fields = array();

  /**
   * Store the errors in case the error set to 0.
   *
   * @var Array
   */
  protected $errors = array();

  /**
   * Constructs a EntityValidateBase object.
   *
   * @param array $plugin
   *   Plugin definition.
   */
  public function __construct($plugin) {
    $this->plugin = $plugin;
    $this->entityType = $plugin['entity_type'];
    $this->bundle = $plugin['bundle'];
  }

  /**
   * {@inheritdoc}
   */
  public function setBundle($bundle) {
    $this->bundle = $bundle;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getBundle() {
    return $this->bundle;
  }

  /**
   * {@inheritdoc}
   */
  public function setEntityType($entity_type) {
    $this->entityType = $entity_type;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityType() {
    return $this->entityType;
  }

  /**
   * {@inheritdoc}
   */
  public function addField($field, $value) {
    $fields = explode(":", $field);

    if (count($fields) == 2) {
      $this->fields[$fields[0]][$fields[1]] = $value;
    }
    else {
      $this->fields[$field] = $value;
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFields() {
    return $this->fields;
  }

  /**
   * {@inheritdoc}
   */
  public function setFields($fields) {
    $this->fields = $fields;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldsInfo() {
    $fields_info = array();
    $entity_info = entity_get_info($this->entityType);
    $keys = $entity_info['entity keys'];

    // When the entity has a label key we need to verify it's not empty.
    if (!empty($keys['label'])) {
      $fields_info[$keys['label']] = array(
        'validators' => array(
          array($this, 'isNotEmpty'),
        ),
      );
    }

    return $fields_info;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($entity) {
    $fields_info = $this->getFieldsInfo();
    $wrapper = entity_metadata_wrapper($this->entityType, $entity);

    // Collect the fields callbacks.
    foreach ($fields_info as $field => $info) {
      if (!empty($info['preprocess'])) {
        $info['preprocess'] = array_unique($info['preprocess']);
        foreach ($info['preprocess'] as $preprocess) {
          $value = $wrapper->__isset($field) ? $wrapper->{$field}->value() : $entity->{$field};

          if ($wrapper->__isset($field)) {
            // Setting the fields value with the wrapper.
            $wrapper->{$field}->set(call_user_func_array($preprocess, array($value, $field)));
          }
        }
      }

      // Loading default value of the fields and the instance.
      $field_info = field_info_field($field);
      $field_type_info = field_info_field_types($field_info['type']);
      $instance_info = field_info_instance($this->entityType, $field, $this->bundle);

      if ($instance_info['required']) {
        $fields_info[$field]['validators'][] = array($this, 'isNotEmpty');
      }

      if (isset($field_type_info['property_type'])) {
        $value = $wrapper->__isset($field) ? $wrapper->{$field}->value() : $entity->{$field};
        $this->isValidValue($value, $field, $field_type_info['property_type']);
      }

      if (!empty($info['validators'])) {
        $info['validators'] = array_unique($info['validators']);
        foreach ($info['validators'] as $validator) {
          $value = $wrapper->__isset($field) ? $wrapper->{$field}->value() : $entity->{$field};
          call_user_func_array($validator, array($value, $field));
        }
      }
    }

    // Throwing exception with the errors.
    if (!empty($this->errors)) {
      $params = array(
        '@errors' => implode(", ", $this->errors),
      );

      throw new \EntityValidatorException(t('The validation process failed: @errors', $params));
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function setError($message) {
    $this->errors[] = $message;
  }

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
  public function isNotEmpty($value, $field) {
    if (empty($value)) {
      $params = array(
        '@field' => $field,
      );

      $this->setError(t("The field @field can't be empty", $params));
    }
  }

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
  public function isText($value, $field) {
    if (!is_string($value)) {
      $params = array(
        '@value' => $value,
      );

      $this->setError('The given value(@value) is not a string', $params);
      return;
    }

    return TRUE;
  }

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
  public function isNumeric($value, $field) {
    if (!is_int($value)) {
      $params = array(
        '@value' => $value,
      );

      $this->setError('The given value(@value) is not an integer', $params);
      return;
    }

    return TRUE;
  }

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
  public function isList($value, $field) {
    if (!is_array($value)) {
      $params = array(
        '@value' => $value,
      );

      $this->setError('The given value(@value) is not an array', $params);
      return;
    }

    return TRUE;
  }

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
  public function isYear($value, $field) {
    if (!is_numeric($value) || (is_numeric($value) && $value > 9999)) {
      $params = array(
        '@value' => $value,
      );

      $this->setError('The given value(@value) is not an year', $params);
      return;
    }

    return TRUE;
  }

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
  public function isUnixTimeStamp($value, $field) {
    if (is_string($value)) {
      $this->setError(t("The time stamp can't be a string"));
      return;
    }

    if (!($value <= PHP_INT_MAX) && ($value >= ~PHP_INT_MAX)) {
      $params = array(
        '@value' => $value,
      );

      $this->setError(t('The give value(@value) is not a time stamp format since the given value is out of range.', $params));
      return;
    }

    return TRUE;
  }

  /**
   * Special validate callback: usually all the validator have two arguments,
   * value and field. This validate method check the value of the field using
   * the entity API module.
   *
   * @param $value
   *  The value of the field.
   * @param $field
   *  The field name.
   * @param $type
   *  The type of the field.
   *
   * @return boolean
   */
  public function isValidValue($value, $field, $type) {
    if (!entity_property_verify_data_type($value, $type)) {
      $params = array(
        '%value' => (String) $value,
        '%field-label' => $field,
      );

      $this->setError(t('The value %value is invalid for the field %field-label', $params));
    }
  }

  /**
   * Change the given value to a date format.
   *
   * @param $value
   *  The value we need to change.
   *
   * @return mixed
   */
  public function preprocessDate($value) {
    return strtotime($value);
  }

  /**
   * Wrap the value to a text format value.
   *
   * @param $value
   *  The value we need to change.
   *
   * @return mixed
   */
  public function preprocessText($value) {
    return array(
      'value' => $value,
    );
  }

  /**
   * Change the given value from a single value to a multiple value.
   *
   * @param $value
   *  The value we need to change.
   *
   * @return mixed
   */
  public function preprocessList($value) {
    return array($value);
  }

  /**
   * Apply array_unique on the given value.
   *
   * @param $value
   *  The value we need to change.
   *
   * @return mixed
   */
  public function preprocessUnique($value) {
    return array_unique($value);
  }
}

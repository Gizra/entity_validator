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
          'isNotEmpty',
        ),
      );
    }

    return $fields_info;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($entity, $silent = FALSE) {
    if (!$fields_info = $this->getFieldsInfo()) {
      return TRUE;
    }

    $wrapper = entity_metadata_wrapper($this->entityType, $entity);

    // Collect the fields callbacks.
    foreach ($fields_info as $field_name => $info) {

      if (!empty($info['preprocess'])) {
        $this->invokeMethods($wrapper->{$field_name}, array_unique($info['preprocess']), TRUE);
      }

      // Loading default value of the fields and the instance.
      $field_info = field_info_field($field_name);
      $field_type_info = field_info_field_types($field_info['type']);
      $instance_info = field_info_instance($this->entityType, $field_name, $this->bundle);

      if ($instance_info['required']) {
        $fields_info[$field_name]['validators'][] = 'isNotEmpty';
      }

      if (isset($field_type_info['property_type'])) {
        $value = isset($wrapper->{$field_name}) ? $wrapper->{$field_name}->value() : $entity->{$field_name};
        $this->isValidValue($field_name, $value, $field_type_info['property_type']);
      }

      if (!empty($info['validators'])) {
        $this->invokeMethods($wrapper->{$field_name}, array_unique($info['validators']));
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

    if (!$handler->validate($entity, TRUE)) {
      $handler->getErrors();
    }

  }

  /**
   * Preprocess the field. This is useful when we need to alter a field before
   * the validation process.
   *
   * @param \EntityMetadataWrapper $property_wrapper
   *  The property wrapper.
   * @param array $methods
   *  Array of callbacks.
   * @param bool $assign_value
   *  Determine if we need to assign the from the callback to the field.
   */
  protected function invokeMethods(EntityMetadataWrapper $property_wrapper, array $methods, $assign_value = FALSE) {
    foreach ($methods as $method) {
      $value = $property_wrapper->value();

      $info = $property_wrapper->info();
      $new_value = $this->{$method}($value, $info['name']);
      if ($assign_value && $new_value != $value) {
        // Setting the fields value with the wrapper.
        $property_wrapper->set($value);
      }
    }
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
   * @param $field
   *  The field name.
   * @param $value
   *  The value of the field.
   *
   * @return boolean
   */
  public function isNotEmpty($field, $value) {
    if (empty($value)) {
      $params = array(
        '@field' => $field,
      );

      $this->setError(t("The field @field can't be empty", $params));
    }
  }

  /**
   * Special validate callback: usually all the validator have two arguments,
   * value and field. This validate method check the value of the field using
   * the entity API module.
   *
   * @param $field
   *  The field name.
   * @param $value
   *  The value of the field.
   * @param $type
   *  The type of the field.
   *
   * @return boolean
   */
  public function isValidValue($field, $value, $type) {
    if (!entity_property_verify_data_type($value, $type)) {
      $params = array(
        '@value' => (String) $value,
        '@field' => $field,
      );

      $error = array(
        'field_foo' => array(
          'message' => 'The value %value is invalid for the field %field-label',
          'params' => $params,
        ),
      );

      $this->setError(t('The value %value is invalid for the field %field-label', $params));
    }
  }
}

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
  public function setFieldsInfo() {
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
  public function getFieldsInfo() {
    $fields = $this->setfieldsInfo();

    foreach ($fields as $field_name => $info) {
      // Loading default value of the fields and the instance.
      $instance_info = field_info_instance($this->entityType, $field_name, $this->bundle);

      if ($instance_info['required']) {
        $fields[$field_name]['validators'][] = 'isNotEmpty';
      }
    }

    return $fields;
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
      $property = isset($info['property']) ? $info['property'] : $field_name;

      if (!empty($info['preprocess'])) {
        $this->invokeMethods($wrapper->{$property}, $info['preprocess'], TRUE);
      }

      // Loading default value of the fields and the instance.
      $field_info = field_info_field($field_name);
      $field_type_info = field_info_field_types($field_info['type']);

      if (isset($field_type_info['property_type'])) {
        $this->isValidValue($field_name, $wrapper->{$property}->value(), $field_type_info['property_type']);
      }

      if (!empty($info['validators'])) {
        $this->invokeMethods($wrapper->{$property}, $info['validators']);
      }
    }

    if (!$errors = $this->getErrors()) {
      return TRUE;
    }

    $errors_list = array();

    foreach ($errors as $field_name => $error) {
      $errors_list[$field_name] = t($error['message'], $error['params']);
    }

    // Throwing exception with the errors.
    if ($silent) {
      return $errors_list;
    }

    $params = array(
      '@errors' => implode(", ", $errors_list),
    );

    throw new \EntityValidatorException(t('The validation process failed: @errors', $params));
  }

  /**
   * Preprocess the field. This is useful when we need to alter a field before
   * the validation process.
   *
   * @param \EntityMetadataWrapper $property_wrapper
   *  The property wrapper.
   * @param array $methods
   *  Array of methods.
   * @param bool $assign_value
   *  Determine if we need to assign the from the callback to the field. Default
   *  to FALSE.
   */
  protected function invokeMethods(EntityMetadataWrapper $property_wrapper, array $methods, $assign_value = FALSE) {
    foreach ($methods as $method) {
      $value = $property_wrapper->value();

      $info = $property_wrapper->info();
      $new_value = $this->{$method}($info['name'], $value);
      if ($assign_value && $new_value != $value) {
        // Setting the fields value with the wrapper.
        $property_wrapper->set($value);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setError($field_name, $message, $params = array()) {
    $this->errors[$field_name] = array('message' => $message, 'params' => $params);
  }

  /**
   * {@inheritdoc}
   */
  public function getErrors() {
    return $this->errors;
  }

  /**
   * Verify the field is not empty.
   *
   * @param $field_name
   *  The field name.
   * @param $value
   *  The value of the field.
   *
   * @return boolean
   */
  public function isNotEmpty($field_name, $value) {
    if (empty($value)) {
      $params = array(
        '@field' => $field_name,
      );

      $this->setError($field_name, "The field @field can't be empty", $params);
    }
  }

  /**
   * Special validate callback: usually all the validator have two arguments,
   * value and field. This validate method check the value of the field using
   * the entity API module.
   *
   * @param $field_name
   *  The field name.
   * @param $value
   *  The value of the field.
   * @param $type
   *  The type of the field.
   *
   * @return boolean
   */
  public function isValidValue($field_name, $value, $type) {
    if (!entity_property_verify_data_type($value, $type)) {
      $params = array(
        '@value' => (String) $value,
        '@field' => $field_name,
      );

      $this->setError($field_name, 'The value @value is invalid for the field @field', $params);
    }
  }
}

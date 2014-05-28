<?php

/**
 * Abstract entity validation.
 */
abstract class AbstractEntityValidate implements EntityValidateInterface {

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
  public function addField($name, $callbacks) {
    $this->fields[$name] = $callbacks;
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
  public function fieldsMetaData() {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function validate() {

    // Collect the fields callbacks.
    if ($validators = $this->fieldsMetaData()) {
       foreach ($validators as $field => $metadata) {
         foreach ($metadata['validators'] as $validator) {
           call_user_func_array($validator, array($this->fields[$field]));
         }

         foreach ($metadata['morphers'] as $validator) {
           $this->fields[$field] = call_user_func_array($validator, array($this->fields[$field]));
         }
       }
    }

    foreach ($this->fields as $field => $value) {
      // Loading some default value of the fields and the instance.
      $field_info = field_info_field($field);
      $field_type_info = field_info_field_types($field_info['type']);
      $instance_info = field_info_instance($this->entityType, $field, $this->bundle);

      if ($instance_info['required'] && empty($value)) {
        $this->setError(t('Field %name is empty', array('%name' => $instance_info['label'])));
      }
      else {
        // Use the entity API validation.
        if (isset($field_type_info['property_type']) && !entity_property_verify_data_type($value, $field_type_info['property_type'])) {
          $params = array(
            '%value' => (String) $value,
            '%field-label' => $instance_info['label'],
          );

          $this->setError(t('The value %value is invalid for the field %field-label', $params));
          continue;
        }
      }
    }

    if (!empty($this->errors)) {
      $params = array(
        '@errors' => implode(", ", $this->errors),
      );
      throw new Exception(t('The validation process failed: @errors', $params));
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
   * {@inheritdoc}
   */
  public function isText($value) {
    if (!is_string($value)) {
      $this->setError('');
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isNumeric($value) {
    // TODO: Implement isNumeric() method.
  }

  /**
   * {@inheritdoc}
   */
  public function isList($value) {
    // TODO: Implement isList() method.
  }

  /**
   * {@inheritdoc}
   */
  public function isYear($value) {
    // TODO: Implement isYear() method.
  }

  /**
   * {@inheritdoc}
   */
  public function isUnixTimeStamp($value) {
    // todo: Fix this when passing timestamp.
    $timestamp = ((string) $value === (int) $value) && ($value <= PHP_INT_MAX) && ($value >= ~PHP_INT_MAX);
    if (!$timestamp) {
      $params = array(
        '@value' => $value,
      );
      $this->setError(t('The give value(@value) is not a time stamp format', $params));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function morphDate($value) {
    return strtotime($value);
  }

  /**
   * {@inheritdoc}
   */
  public function morphText($value) {
    // TODO: Implement morphText() method.
  }

  /**
   * {@inheritdoc}
   */
  public function morphList($value) {
    // TODO: Implement morphList() method.
  }

  /**
   * {@inheritdoc}
   */
  public function morphUnique($value) {
    // TODO: Implement morphUnique() method.
  }
}

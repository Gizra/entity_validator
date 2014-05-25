<?php

class NodeValidate {

  protected $label;

  protected $fields;

  protected $bundle;

  public function setLabel($label = NULL) {
    $this->label = $label;

    return $this;
  }

  public function setBundle($bundle) {
    $this->bundle = $bundle;

    return $this;
  }

  public function addField($name, $value) {
     $this->fields[$name] = $value;

    return $this;
  }

  public function validate() {
    if (empty($this->label)) {
      $this->setError(t('The title if missing'));
    }

    foreach ($this->fields as $field => $value) {
      $field_info = field_info_field($field);
      $field_type_info = field_info_field_types($field_info['type']);
      $instance_info = field_info_instance('node', $field, $this->bundle);

      if ($instance_info['required'] && empty($value)) {
        $this->setError(t('Field %name is empty', array('%name' => $instance_info['label'])));
      }
      else {
        foreach ($field_type_info['node_validator_callback'] as $callback) {
          if (!call_user_func_array($callback, array($value))) {
            $this->setError(t('The given format is not valid: %format', array('%format' => $value)));
          }
        }
      }
    }
  }

  public function setError($message) {
    drupal_set_message($message, 'error');
  }
}

<?php
/**
 * @file
 *
 * Holds the entity validator base class.
 */

namespace Drupal\entity_validator\Base;
use Drupal\Component\Plugin\PluginBase;
use Drupal\entity_validator\Exception\EntityValidatorException;
use Drupal\entity_validator\FieldsInfo;
use Drupal\entity_validator\Interfaces\EntityValidateInterface;

/**
 * Abstract entity validation.
 */
abstract class EntityValidateBase extends PluginBase implements EntityValidateInterface {

  /**
   * The entity type.
   *
   * @var string
   */
  protected $entityType;

  /**
   * @var String.
   *
   * The bundle of the node.
   */
  protected $bundle;

  /**
   * @var array
   *
   * List of fields keyed by machine name and valued with the field's value.
   *
   * Array with the optional values:
   * - "property": The entity property (e.g. "title", "nid").
   * - "sub_property": A sub property name of a property to take from it the
   *   content. This can be used for example on a text field with filtered text
   *   input format where we would need to do $wrapper->body->value->value().
   *   Defaults to FALSE.
   */
  protected $publicFields = array();

  /**
   * @var Array
   *
   * Store the errors in case the error set to 0.
   */
  protected $errors = array();

  /**
   * @var FieldsInfo
   *
   * The fields info object.
   */
  protected $fieldsInfo;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    $this->entityType = $plugin_definition['entity_type'];
    $this->bundle = $plugin_definition['bundle'];
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
  public function publicFieldsInfo() {
    $public_fields = array();
    $entity_info = entity_get_info($this->entityType);
    $keys = $entity_info['entity keys'];
    $this->fieldsInfo = FieldsInfo::setFieldInfo($public_fields, $this);

    // When the entity has a label key we need to verify it's not empty.
    if (!empty($keys['label'])) {
      FieldsInfo::setFieldInfo($public_fields[$keys['label']])->setRequired();

      // todo: Handle.
//      $this->fieldsInfo
//        ->setProperty('label')
//        ->setRequired();
    }

    $instances_info = field_info_instances($this->getEntityType(), $this->getBundle());
    foreach ($instances_info as $instance_info) {
      $field_info = field_info_field($instance_info['field_name']);

      $fields_handler = FieldsInfo::setFieldInfo($public_fields[$instance_info['field_name']], $this);

      if ($instance_info['required']) {
        // Validate field is not empty.
        $fields_handler->setRequired();
      }

      if ($field_info['type'] == 'image') {
        // Validate the image dimensions.
        $fields_handler->addCallback('validateImageSize');
      }

      if (in_array($field_info['type'], array('image', 'file'))) {
        // Validate the file type.
        $fields_handler->addCallback('validateFileExtension');
      }

      // Check field is valid using the wrapper.
      $fields_handler->addCallback('isValidValue');
    }

    return $public_fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getPublicFields() {
    $public_fields = $this->publicFieldsInfo();
    foreach ($public_fields as $property => &$public_field) {

      $field = FieldsInfo::setFieldInfo($public_field, $this)
        ->setProperty($property);

      if (empty($public_field['validators'])) {
        $field->addCallback('isValidValue');

        if ($public_field['required']) {
          // Property is required.
          $field->addCallback('isNotEmpty');
        }
      }
    }

    return $public_fields;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($entity, $silent = FALSE) {
    // Clear any previous error messages.
    $this->clearErrors();
    if (!$public_fields = $this->getPublicFields()) {
      return TRUE;
    }

    $wrapper = entity_metadata_wrapper($this->entityType, $entity);

    // Collect the fields callbacks.
    foreach ($public_fields as $public_field) {
      $property = $public_field['property'];

      $value = $this->publicValue($public_field, $entity);
      $property_wrapper = $this->propertyWrapper($public_field, $entity);
      foreach ($public_field['validators'] as $validator) {
        if (!$validator) {
          continue;
        }
        // Property has value.
        $validator[0]->{$validator[1]}($property, $value, $wrapper, $property_wrapper);
      }
    }

    if (!$errors = $this->getErrors()) {
      return TRUE;
    }

    if ($silent) {
      // Don't throw an error, just indicate validation failed.
      return FALSE;
    }

    $params = array('@errors' => $errors);
    throw new EntityValidatorException(format_string('The validation process failed: @errors', $params));
  }

  /**
   * {@inheritdoc}
   */
  public function setError($field_name, $message, $params = array()) {
    $params['@field'] = $field_name;
    $this->errors[$field_name][] = array('message' => $message, 'params' => $params);
  }

  /**
   * {@inheritdoc}
   */
  public function getErrors($squash = TRUE) {
    if (!$squash) {
      return $this->errors;
    }

    $return = array();
    foreach ($this->errors as $errors) {
      foreach ($errors as $error) {
        $error += array('params' => array());
        $return[] = format_string($error['message'], $error['params']);
      }
    }

    return implode("\n\r", $return);
  }

  /**
   * {@inheritdoc}
   */
  public function clearErrors() {
    $this->errors = array();
  }

  /**
   * Verify the field is not empty.
   *
   * @param string $field_name
   *   The field name.
   * @param mixed $value
   *   The value of the field.
   * @param \EntityMetadataWrapper $wrapper
   *   The wrapped entity.
   * @param \EntityMetadataWrapper $property_wrapper
   *   The wrapped property.
   */
  protected function isNotEmpty($field_name, $value, \EntityMetadataWrapper $wrapper, \EntityMetadataWrapper $property_wrapper) {
    if (empty($value)) {
      $params = array('@field' => $field_name);
      $this->setError($field_name, 'The field @field cannot be empty.', $params);
    }
  }

  /**
   * Check the value of the field using the entity API module.
   *
   * @param string $field_name
   *   The field name.
   * @param mixed $value
   *   The value of the field.
   * @param \EntityMetadataWrapper $wrapper
   *   The wrapped entity.
   * @param \EntityMetadataWrapper $property_wrapper
   *   The wrapped property.
   */
  protected function isValidValue($field_name, $value, \EntityMetadataWrapper $wrapper, \EntityMetadataWrapper $property_wrapper) {
    // Loading default value of the fields and the instance.
    if (!$field_info = field_info_field($field_name)) {
      // Not a field.
      return;
    }

    $field_type_info = field_info_field_types($field_info['type']);
    if (empty($field_type_info['property_type'])) {
      return;
    }

    if (!$property_wrapper->validate($value)) {
      $this->setError($field_name, 'Invalid value for the field @field.');
    }
  }

  /**
   * Validate the field image's by checking the image size is valid.
   *
   * @param string $field_name
   *   The field name.
   * @param mixed $value
   *   The value of the field.
   * @param \EntityMetadataWrapper $wrapper
   *   The wrapped entity.
   * @param \EntityMetadataWrapper $property_wrapper
   *   The wrapped property.
   */
  protected function validateImageSize($field_name, $value, \EntityMetadataWrapper $wrapper, \EntityMetadataWrapper $property_wrapper) {
    if (empty($value)) {
      return;
    }

    $info = field_info_instance($this->getEntityType(), $field_name, $this->getBundle());
    $settings = $info['settings'];

    $file = file_load($value['fid']);
    $url = file_create_url($file->uri);
    $size = getimagesize($url);

    $value = array(
      'width' => $size['0'],
      'height' => $size['1'],
    );

    $params = array(
      '@width' => $value['width'],
      '@height' => $value['height'],
    );

    if (!empty($settings['max_resolution'])) {
      list($max_height, $max_width) = explode("X", $settings['max_resolution']);

      $params += array(
        '@max-width' => $max_width,
        '@max-height' => $max_height,
      );

      if ($value['width'] > $max_width) {
        $this->setError($field_name, 'The width of the image(@width) is bigger then the allowed size(@max-width)', $params);
      }

      if ($value['height'] > $max_height) {
        $this->setError($field_name, 'The height of the image(@height) is bigger then the allowed size(@max-height)', $params);
      }
    }

    if (!empty($settings['min_resolution'])) {
      list($min_height, $min_width) = explode("X", $settings['min_resolution']);
      $params += array(
        '@min-width' => $min_width,
        '@min-height' => $min_height,
      );

      if ($value['width'] < $min_width) {
        $this->setError($field_name, 'The width of the image(@width) is bigger then the allowed size(@min-width)', $params);
      }

      if ($value['height'] < $min_height) {
        $this->setError($field_name, 'The height of the image(@height) is bigger then the allowed size(@min-height)', $params);
      }
    }
  }

  /**
   * Validate the file extension.
   *
   * @param string $field_name
   *   The field name.
   * @param mixed $value
   *   The value of the field.
   * @param \EntityMetadataWrapper $wrapper
   *   The wrapped entity.
   * @param \EntityMetadataWrapper $property_wrapper
   *   The wrapped property.
   */
  protected function validateFileExtension($field_name, $value, \EntityMetadataWrapper $wrapper, \EntityMetadataWrapper $property_wrapper) {
    if (empty($value)) {
      return;
    }

    $info = field_info_instance($this->getEntityType(), $field_name, $this->getBundle());
    $settings = $info['settings'];

    $file = file_load($value['fid']);

    $extensions = explode('.', $file->filename);
    $extension = end($extensions);

    if (!in_array($extension, explode(" ", $settings['file_extensions']))) {
      $params = array(
        '@file-name' => $file->filename,
        '@extension' => $extension,
        '@extensions' => $settings['file_extensions'],
      );
      $this->setError($field_name, 'The file (@file-name) extension (@extension) did not match the allowed extensions: @extensions', $params);
    }
  }

  /**
   * Get the value for a public field based on the field definitions.
   *
   * @param array $public_field
   *   The field definition.
   * @param object $entity
   *   The entity.
   *
   * @return mixed
   *   The contents of the field.
   */
  protected function publicValue($public_field, $entity) {
    return $this->propertyWrapper($public_field, $entity)->value();
  }

  /**
   * Get the property wrapper.
   *
   * @param array $public_field
   *   The field definition.
   * @param object $entity
   *   The entity.
   *
   * @return \EntityStructureWrapper
   *   The property wrapper
   */
  protected function propertyWrapper($public_field, $entity) {
    $property = $public_field['property'];
    $wrapper = entity_metadata_wrapper($this->entityType, $entity);
    $property_wrapper = $wrapper->{$property};

    if (!empty($public_field['sub_property']) && $property_wrapper->value()) {
      try {
        $property_wrapper = $property_wrapper->{$public_field['sub_property']};
      }
      catch (\EntityMetadataWrapperException $e) {
        throw new EntityValidatorException(format_string('Server configuration error, the validator for @property is invalid.', array(
          '@property' => $public_field['property'],
        )));
      }
    }
    return $property_wrapper;
  }

}

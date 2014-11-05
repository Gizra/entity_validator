<?php

/**
 * @file
 * Contains EntityValidatorExampleArticleValidator.
 */

class EntityValidatorExampleArticleValidator extends EntityValidateBase {

  /**
   * Overrides EntityValidateBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['title']['validators'][] = array($this, 'validateBodyText');

    $public_fields['body'] = array(
      'required' => TRUE,
      'sub_property' => 'value',
      'validators' => array(
        array($this, 'validateBodyText')
      ),
    );

    return $public_fields;
  }

  /**
   * Validate the title is at least 3 characters long.
   *
   * @param string $field_name
   *   The field name.
   * @param mixed $value
   *   The value of the field.
   * @param EntityMetadataWrapper $wrapper
   *   The wrapped entity.
   * @param EntityMetadataWrapper $property_wrapper
   *   The wrapped property.
   */
  public function validateTitleText($field_name, $value, EntityMetadataWrapper $wrapper, EntityMetadataWrapper $property_wrapper) {
    if (strlen($value) < 3) {
      $this->setError($field_name, 'The @field should be at least 3 characters long.');
    }
  }

  /**
   * Validate the description has the word "Drupal".
   *
   * @param string $field_name
   *   The field name.
   * @param mixed $value
   *   The value of the field.
   * @param EntityMetadataWrapper $wrapper
   *   The wrapped entity.
   * @param EntityMetadataWrapper $property_wrapper
   *   The wrapped property.
   */
  public function validateBodyText($field_name, $value, EntityMetadataWrapper $wrapper, EntityMetadataWrapper $property_wrapper) {
    if (strpos($value, 'Drupal') === FALSE) {
      $this->setError($field_name, 'The @field should have the word "Drupal".');
    }
  }

}

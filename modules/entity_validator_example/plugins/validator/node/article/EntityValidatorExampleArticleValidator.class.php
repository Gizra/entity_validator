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

    $public_fields['title']['validators'][] = 'validateTitleText';

    $public_fields['body'] = array(
      'required' => TRUE,
      'sub_property' => 'value',
      'validators' => array(
        'validateBodyText'
      ),
    );

    return $public_fields;
  }

  /**
   * Validate the title is at least 3 characters long.
   */
  public function validateTitleText($field_name, $value) {
    if (strlen($value) < 3) {
      $this->setError($field_name, 'The @field should be at least 3 characters long.');
    }
  }

  /**
   * Validate the description has the word "Drupal".
   */
  public function validateBodyText($field_name, $value) {
    if (strpos($value, 'Drupal') === FALSE) {
      $this->setError($field_name, 'The @field should have the word "Drupal".');
    }
  }
}

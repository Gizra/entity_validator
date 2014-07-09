<?php

/**
 * @file
 * Contains EntityValidatorExampleArticleValidator.
 */

class EntityValidatorExampleArticleValidator extends EntityValidateBase {

  /**
   * Overrides NodeValidate::getFieldsInfo().
   */
  public function setFieldsInfo() {
    $fields = parent::setFieldsInfo();

    $fields['title']['validators'][] = 'validateTitleText';
    $fields['field_image']['validators'][] = 'isNotEmpty';

    return $fields;
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
   * Verify the field is empty.
   */
  public function validateIsEmpty($field_name, $value) {
    if (!empty($value)) {
      $this->setError($field_name, 'The field @field need to be empty.');
    }
  }
}

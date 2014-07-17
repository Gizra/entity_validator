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
    $fields['body']['validators'][] = 'validateBodyText';

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
   * Validate the description has the word "Gizra".
   */
  public function validateBodyText($field_name, $value) {
    if (empty($value['value']) || strpos($value['value'], 'Gizra') === FALSE) {
      $this->setError($field_name, 'The @field should have the word "Gizra".');
    }
  }
}

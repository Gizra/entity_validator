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

    $fields['author'] = array(
      'validators' => array(
        'validateAuthenticatedUser',
      ),
    );

    return $fields;
  }

  /**
   * Validating the node author belong to authenticated user.
   */
  public function validateAuthenticatedUser($field_name, $value) {
    if (!$value->uid) {
      $this->setError($field_name, 'The author of the node must be authenticated user');
    }
  }
}

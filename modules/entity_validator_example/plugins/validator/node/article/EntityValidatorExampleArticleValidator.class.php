<?php

/**
 * @file
 * Contains EntityValidatorExampleArticleValidator.
 */

class EntityValidatorExampleArticleValidator extends EntityValidateBase {

  /**
   * Overrides NodeValidate::getFieldsInfo().
   */
  public function fieldsInfo() {
    $fields = parent::fieldsInfo();

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
      $this->setError(t('The author of the node must be authenticated user'));
    }
  }
}

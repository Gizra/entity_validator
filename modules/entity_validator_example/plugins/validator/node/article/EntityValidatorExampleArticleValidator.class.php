<?php

/**
 * @file
 * Contains EntityValidatorExampleArticleValidator.
 */

class EntityValidatorExampleArticleValidator extends EntityValidateBase {

  /**
   * Overrides NodeValidate::getFieldsInfo().
   */
  public function getFieldsInfo() {
    $fields = parent::getFieldsInfo();

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
  public function validateAuthenticatedUser($value) {
    if (!$value->uid) {
      $this->setError(t('The author of the node must be authenticated user'));
    }
  }
}

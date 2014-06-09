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

    $fields['uid'] = array(
      'validators' => array(
        array($this, 'validateAuthenticatedUser'),
      ),
    );

    return $fields;
  }

  /**
   * Validating the node author belong to authenticated user.
   */
  public function validateAuthenticatedUser($value, $field) {
    if (!$value) {
      $this->setError(t('The author of the node must be authenticated user'));
    }
  }
}

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

    $fields['field_date'] = array(
      'validators' => array(
        array($this, 'isUnixTimeStamp'),
      ),
      'preprocess' => array(
        array($this, 'preprocessDate'),
      ),
    );

    return $fields;
  }
}

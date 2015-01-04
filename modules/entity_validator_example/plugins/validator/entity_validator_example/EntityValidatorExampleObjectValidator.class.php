<?php

class EntityValidatorExampleObjectValidator extends ObjectValidateBase {

  /**
   * Overrides ObjectValidateBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $fields = parent::publicFieldsInfo();

    FieldsInfo::setFieldInfo($fields['title'], $this)
      ->addCallback('validateTitleText');

    FieldsInfo::setFieldInfo($fields['created'], $this)
      ->addCallback('validateUnixTimeStamp');

    return $fields;
  }

  /**
   * Validate the title is at least 3 characters long.
   *
   * @param string $property
   *   The field name.
   * @param mixed $value
   *   The value of the field.
   * @param \stdClass $object
   *   The object we need to validate.
   */
  public function validateTitleText($property, $value, $object) {
    if (strlen($value) < 3) {
      $this->setError($property, 'The @property property should be at least 3 characters long.');
    }
  }
}

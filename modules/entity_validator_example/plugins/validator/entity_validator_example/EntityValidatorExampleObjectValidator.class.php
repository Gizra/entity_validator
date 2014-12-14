<?php

class EntityValidatorExampleObjectValidator extends ObjectValidateBase {

  /**
   * Overrides ObjectValidateBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $fields = parent::publicFieldsInfo();

    $fields['title']['validators'][] = array($this, 'validateTitleText');
    $fields['created']['validators'][] = array($this, 'validateUnixTimeStamp');

    return $fields;
  }

  /**
   * Validate the title is at least 3 characters long.
   *
   * @param string $property
   *   The field name.
   * @param mixed $value
   *   The value of the field.
   */
  public function validateTitleText($property, $value) {
    if (strlen($value) < 3) {
      $this->setError($property, 'The @property property should be at least 3 characters long.');
    }
  }

  /**
   * Validate the unix time stamp format.
   *
   * @param string $property
   *   The field name.
   * @param mixed $value
   *   The value of the field.
   */
  public function validateUnixTimeStamp($property, $value) {
    if ($value < 7200) {
      $this->setError($property, 'The @property property should be a valid unix time stamp.');
    }
  }
}

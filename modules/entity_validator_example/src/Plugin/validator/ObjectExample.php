<?php

namespace Drupal\entity_validator_example\Plugin\validator;

use Drupal\entity_validator\Base\ObjectValidateBase;
use Drupal\entity_validator\FieldsInfo;

/**
 * @package Drupal\entity_validator_example\Plugin\validator
 *
 * @Validator(
 *  label = "Entity Validator Example",
 *  description = "Validate entity validator example objects",
 *  schema = "entity_validator_example"
 * )
 */
class ObjectExample extends ObjectValidateBase {

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

<?php

namespace Drupal\entity_validator_example\Plugin\validator;

use Drupal\entity_validator\Base\EntityValidateBase;
use Drupal\entity_validator\FieldsInfo;

/**
 * @package Drupal\entity_validator_example\Plugin\validator
 *
 * @Validator(
 *  id = "node_article",
 *  label = "Article",
 *  description = "Validate the article content type.",
 *  entity_type = "node",
 *  bundle = "article"
 * )
 */
class NodeArticle extends EntityValidateBase {

  /**
   * Overrides EntityValidateBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    FieldsInfo::setFieldInfo($public_fields['title'], $this)
      ->addCallback('validateTitleText');

    FieldsInfo::setFieldInfo($public_fields['body'], $this)
      ->setSubProperty('body')
      ->addCallback('validateBodyText');

    return $public_fields;
  }

  /**
   * Validate the title is at least 3 characters long.
   *
   * @param string $field_name
   *   The field name.
   * @param mixed $value
   *   The value of the field.
   * @param \EntityMetadataWrapper $wrapper
   *   The wrapped entity.
   * @param \EntityMetadataWrapper $property_wrapper
   *   The wrapped property.
   */
  public function validateTitleText($field_name, $value, \EntityMetadataWrapper $wrapper, \EntityMetadataWrapper $property_wrapper) {
    if (strlen($value) < 3) {
      $this->setError($field_name, 'The @field should be at least 3 characters long.');
    }
  }

  /**
   * Validate the description has the word "Drupal".
   *
   * @param string $field_name
   *   The field name.
   * @param mixed $value
   *   The value of the field.
   * @param \EntityMetadataWrapper $wrapper
   *   The wrapped entity.
   * @param \EntityMetadataWrapper $property_wrapper
   *   The wrapped property.
   */
  public function validateBodyText($field_name, $value, \EntityMetadataWrapper $wrapper, \EntityMetadataWrapper $property_wrapper) {
    if (strpos($value, 'Drupal') === FALSE) {
      $this->setError($field_name, 'The @field should have the word "Drupal".');
    }
  }

}

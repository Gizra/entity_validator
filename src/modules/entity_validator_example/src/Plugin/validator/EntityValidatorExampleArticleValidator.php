<?php

$plugin = array(
  'description' => t('Validate the article content type.'),
  'entity_type' => 'node',
  'bundle' => 'article',
  'class' => 'EntityValidatorExampleArticleValidator',
);



/**
 * @EntityValidator(
 *  id = node-article,
 *  label = @Translation('Article'),
 *  description = @Translation('Validate the article of content type'),
 *  entity_type = 'node',
 *  bundle = 'article'
 * )
 */
class EntityValidatorExampleArticleValidator extends EntityValidateBase {

  /**
   * Overrides EntityValidateBase::getFieldsInfo().
   */
  public function getFieldsInfo() {
    $fields = parent::getFieldsInfo();

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
    if (empty($value['value']) || strpos($value['value'], 'Drupal') === FALSE) {
      $this->setError($field_name, 'The @field should have the word "Drupal".');
    }
  }
}

<?php

class NodeValidatorExampleArticleValidator extends NodeValidate {

  /**
   * {@inheritdoc}
   */
  public function fieldsMetaData() {
    return array(
      'field_date' => array(
        'validators' => array(
          array($this, 'isUnixTimeStamp'),
        ),
        'morphers' => array(
          array($this, 'morphDate'),
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function validate() {
    if (in_array($this->fields['title'], array('foo', 'bar'))) {
      $this->setError(t("The title of the article can't bee foo or bar"));
    }
    parent::validate();
  }
}

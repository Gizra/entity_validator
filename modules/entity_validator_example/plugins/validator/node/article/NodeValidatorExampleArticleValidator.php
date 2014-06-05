<?php

class NodeValidatorExampleArticleValidator extends NodeValidate {

  /**
   * {@inheritdoc}
   */
  public function getFieldsInfo() {
    return parent::getFieldsInfo() +  array(
      'field_date' => array(
        'validators' => array(
          array($this, 'isUnixTimeStamp'),
        ),
        'preprocess' => array(
          array($this, 'preprocessDate'),
        ),
      ),
    );
  }
}

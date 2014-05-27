<?php

class NodeValidatorExampleArticleValidator extends NodeValidate {

  public function validate() {
    if (in_array($this->fields['title'], array('foo', 'bar'))) {
      $this->setError(t("The title of the article can't bee foo or bar"));
    }
    parent::validate();
  }
}

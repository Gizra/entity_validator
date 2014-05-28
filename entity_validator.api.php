<?php

/**
 * Validator demo. This is a demo for how to use the validator.
 */
function my_module_validator_demo() {
  entity_validator_load_validator('node', 'article')
    ->addField('title', '');

  return '';
}

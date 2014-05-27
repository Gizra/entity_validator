<?php

/**
 * Implements hook_field_info_alter().
 */
function my_module_field_info_alter(&$info) {
  $info['datetime']['node_validator_callback'] = array(
    'my_module_date_validate',
  );
}

/**
 * Validator demo. This is a demo for how to use the validator.
 */
function my_module_validator_demo() {
  entity_validator_load_validator('node', 'article')
    ->addField('title', '');

  return '';
}

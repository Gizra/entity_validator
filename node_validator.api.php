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
 * Validator callback;
 *
 * @param NodeValidate $validator
 *  The node validator object.
 * @param $value
 *  The value of field we need to validate.
 *
 * @return int
 */
function my_module_date_validate(NodeValidate $validator, $value) {
  return strtotime($value);
}

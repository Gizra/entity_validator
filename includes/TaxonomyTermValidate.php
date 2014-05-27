<?php

/**
 * Taxonomy term validation.
 *
 * @todo: Check if we need to code the validation.
 */
class TaxonomyTermValidate extends AbstractEntityValidate {

  public function __construct() {
    $this->entityType = 'taxonomy_term';
  }
}

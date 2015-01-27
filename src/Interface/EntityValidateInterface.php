<?php

interface EntityValidateInterface extends ValidateInterface {

  /**
   * Set the bundle of the node.
   *
   * @param $bundle
   *   The bundle machine name.
   *
   * @return $this
   */
  public function setBundle($bundle);

  /**
   * Retrieve the bundle.
   */
  public function getBundle();

  /**
   * Set the entity type.
   *
   * @param $entity
   *   The name of the entity.
   *
   * @return $this
   */
  public function setEntityType($entity);

  /**
   * Return the entity type.
   *
   * @return String
   */
  public function getEntityType();
}

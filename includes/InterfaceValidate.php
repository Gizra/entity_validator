<?php

interface Validate {

  /**
   * Setting the label of the node.
   *
   * @param null $label
   *  The label of the node.
   *
   * @return $this
   */
  public function setLabel($label = NULL);

  /**
   * Set the bundle of the node.
   *
   * @param $bundle
   *  The bundle machine name.
   *
   * @return $this
   */
  public function setBundle($bundle);

  /**
   * Set the entity type.
   *
   * @param $entity
   *  The name of the entity.
   *
   * @return $this
   */
  public function setEntity($entity);

  /**
   * Adding the field the validation process.
   *
   * @param $name
   *  The machine name of the field.
   * @param $value
   *  The value of the field.
   *
   * @return $this
   */
  public function addField($name, $value);

  /**
   * Initialize the validate process.
   */
  public function validate();

  /**
   * Set error.
   *
   * @param $message
   *  Set the error message.
   *
   * @throws Exception
   *  When setting the error level to 1 exception will be thrown with the value
   *  of the error.
   */
  public function setError($message);
}

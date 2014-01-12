<?php

/**
 * @file
 * Contains \Afas\Component\Test\TestItem.
 *
 * This class is used solely for playing with the list interface.
 */

namespace Afas\Component\Test;

class TestItem {
  private $value;

  /**
   * Set the value.
   */
  public function __construct($value = NULL) {
    $this->setValue($value);
  }

  /**
   * Set the value.
   */
  public function setValue($value) {
    $this->value = $value;
  }

  /**
   * Return the value.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   *
   */
  public function __toString() {
    return (string) $this->value;
  }
}

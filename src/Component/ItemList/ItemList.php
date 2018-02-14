<?php

namespace Afas\Component\ItemList;

/**
 * Base class for a list of items.
 */
abstract class ItemList implements \IteratorAggregate, ListInterface {

  /**
   * Numerically indexed array items.
   *
   * @var array
   */
  private $list = [];

  /**
   * Implements \IteratorAggregate::getIterator().
   */
  public function getIterator() {
    return new \ArrayIterator($this->list);
  }

  /**
   * Implements \Countable::count().
   */
  public function count() {
    return isset($this->list) ? count($this->list) : 0;
  }

  /**
   * Adds an item to the list.
   *
   * @return self
   *   An instance of this class.
   */
  protected function addItem($item, $key = NULL) {
    if (is_null($key)) {
      $this->list[] = $item;
    }
    else {
      $this->list[$key] = $item;
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getItems() {
    return $this->list;
  }

  /**
   * Removes an item from the list.
   *
   * @return self
   *   An instance of this class.
   */
  protected function removeItem($key) {
    unset($this->list[$key]);
    return $this;
  }

}

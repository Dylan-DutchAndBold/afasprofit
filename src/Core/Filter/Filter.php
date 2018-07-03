<?php

namespace Afas\Core\Filter;

use DOMDocument;
use InvalidArgumentException;

/**
 * Class for setting a filter for a Profit GetConnector.
 */
class Filter implements FilterInterface {

  // --------------------------------------------------------------
  // PROPERTIES
  // --------------------------------------------------------------

  /**
   * The name of the field to filter on.
   *
   * @var string
   */
  protected $field;

  /**
   * The value to test the field against.
   *
   * @var mixed
   */
  protected $value;

  /**
   * The comparison operator, such as =, <, or >=.
   *
   * @var int
   *
   * @see defined class constants
   */
  protected $operator;

  // --------------------------------------------------------------
  // CONSTRUCT
  // --------------------------------------------------------------

  /**
   * Constructs a new Filter object.
   *
   * @param string $field
   *   The name of the field to filter on.
   * @param mixed $value
   *   (optional) The value to test the field against.
   *   Defaults to NULL.
   * @param mixed $operator
   *   (optional) The comparison operator, such as =, <, or >=.
   *   Defaults to:
   *   - static::OPERATOR_EQ if value is set;
   *   - NULL otherwise.
   */
  public function __construct($field, $value = NULL, $operator = NULL) {
    if (!isset($operator) && isset($value)) {
      $operator = static::OPERATOR_EQ;
    }
    $this->setField($field);
    $this->setValue($value);
    if (isset($operator)) {
      $this->setOperator($operator);
    }
  }

  // --------------------------------------------------------------
  // GETTERS
  // --------------------------------------------------------------

  /**
   * Magic getter.
   *
   * @param string $key
   *   The property to get.
   *
   * @return mixed
   *   Property's value.
   */
  public function __get($key) {
    switch ($key) {
      case 'field':
        return $this->field;

      case 'value':
        return $this->value;

      case 'operator':
        return $this->operator;
    }
  }

  /**
   * Return XML string.
   *
   * @return string
   *   The filter as XML.
   */
  public function compile() {
    $doc = new DOMDocument();
    $field = $doc->createElement('Field');
    $doc->appendChild($field);

    // Set field ID and operator.
    $field->setAttribute('FieldId', $this->field);
    $field->setAttribute('OperatorType', $this->operator);

    // Set value, if there is one.
    if (!is_null($this->value) && $this->value !== '') {
      $text = $doc->createTextNode($this->value);
      $field->appendChild($text);
    }

    // Return XML as string.
    return $doc->saveXML($field);
  }

  /**
   * Implements PHP magic __toString method to convert the filter to string.
   *
   * @return string
   *   A string version of the filter.
   */
  public function __toString() {
    return $this->compile();
  }

  // --------------------------------------------------------------
  // SETTERS
  // --------------------------------------------------------------

  /**
   * Magic setter.
   */
  public function __set($key, $value) {
    switch ($key) {
      case 'field':
        $this->setField($value);
        break;

      case 'value':
        $this->setValue($value);
        break;

      case 'operator':
        $this->setOperator($value);
        break;
    }
  }

  /**
   * Sets field of filter.
   *
   * @param string $field
   *   The field to set.
   *
   * @throws \InvalidArgumentException
   *   If the field is not of the right data type.
   *
   * @return self
   *   The called object.
   */
  public function setField($field) {
    if (!is_string($field)) {
      throw new InvalidArgumentException('Field must be a string.');
    }
    $this->field = $field;
    return $this;
  }

  /**
   * Sets value of filter.
   *
   * The operator may change, depending on the value that is set.
   *
   * @param mixed $value
   *   The value to set.
   *
   * @return self
   *   The called object.
   */
  public function setValue($value) {
    if (is_null($value)) {
      $this->value = $value;
      $this->setOperator(static::OPERATOR_EMPTY);
    }
    elseif (is_scalar($value)) {
      $this->value = $value;
    }
    return $this;
    // @todo other cases.
  }

  /**
   * Sets operator of filter (e.g., 'equal').
   *
   * Accepts both integers and strings.
   *
   * @param mixed $operator
   *   The operator to set.
   *
   * @throws \InvalidArgumentException
   *   If an invalid operator is set.
   *
   * @return self
   *   The called object.
   */
  public function setOperator($operator) {
    if (is_numeric($operator)) {
      if ($operator > 0 && $operator < 16) {
        $this->operator = (int) $operator;

        // Erase value if 'empty' or 'not empty' operator is chosen.
        switch ($this->operator) {
          case static::OPERATOR_NOT_EMPTY:
          case static::OPERATOR_EMPTY:
            $this->value = NULL;
            break;
        }
        return $this;
      }
    }
    elseif (is_string($operator)) {
      $operator = strtolower($operator);
      switch ($operator) {
        case '=':
        case '==':
        case 'eq':
        case 'equal':
          return $this->setOperator(static::OPERATOR_EQ);

        case '>':
        case 'gt':
        case 'greater than':
          return $this->setOperator(static::OPERATOR_GT);

        case '>=':
        case 'ge':
        case 'greater than or equal':
          return $this->setOperator(static::OPERATOR_GE);

        case '<':
        case 'lt':
        case 'less than':
        case 'lesser than':
          return $this->setOperator(static::OPERATOR_LT);

        case '<=':
        case 'le':
        case 'less than or equal':
        case 'lesser than or equal':
          return $this->setOperator(static::OPERATOR_LE);

        case '!=':
        case '<>':
        case 'ne':
        case 'not equal':
          return $this->setOperator(static::OPERATOR_NE);

        case 'null':
        case 'is null':
        case 'empty':
          return $this->setOperator(static::OPERATOR_EMPTY);

        case 'not null':
        case 'is not null':
        case 'not empty':
          return $this->setOperator(static::OPERATOR_NOT_EMPTY);

        case 'contains':
        case 'like':
          return $this->setOperator(static::OPERATOR_CONTAINS);

        case 'not like':
        case 'not contains':
        case 'contains not':
        case 'does not contain':
          return $this->setOperator(static::OPERATOR_CONTAINS_NOT);

        case 'starts':
        case 'starts with':
          return $this->setOperator(static::OPERATOR_STARTS_WITH);

        case 'not starts':
        case 'starts not with':
        case 'does not start with':
          return $this->setOperator(static::OPERATOR_STARTS_NOT_WITH);

        case 'ends':
        case 'ends with':
          return $this->setOperator(static::OPERATOR_ENDS_WITH);

        case 'not ends':
        case 'ends not with':
        case 'does not end with':
          return $this->setOperator(static::OPERATOR_ENDS_NOT_WITH);

        case 'quick':
          return $this->setOperator(static::OPERATOR_QUICK);
      }
    }
    throw new InvalidArgumentException('The operator "' . $operator . '" is not supported.');
  }

}

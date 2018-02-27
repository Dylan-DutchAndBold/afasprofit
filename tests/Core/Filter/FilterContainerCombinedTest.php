<?php

namespace Afas\Tests\Core\Filter;

use Afas\Core\Filter\FilterContainer;
use Afas\Tests\TestBase;

/**
 * Tests filter container in combination with filter groups and filters.
 *
 * @group AfasCoreFilter
 */
class FilterContainerCombinedTest extends TestBase {

  /**
   * Tests if the right output is generated using a single filter.
   *
   * @dataProvider singleFilterOperatorProvider()
   */
  public function testSingleFilterOperator($expected, $operator = NULL) {
    $container = new FilterContainer();
    $container->filter('item_id', 0, $operator);
    $this->assertXmlStringEqualsXmlString($expected, $container->compile());
  }

  /**
   * Data provider for testSingleFilter().
   */
  public function singleFilterOperatorProvider() {
    return [
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="1">0</Field></Filter></Filters>',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="1">0</Field></Filter></Filters>',
        '=',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="1">0</Field></Filter></Filters>',
        '==',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="1">0</Field></Filter></Filters>',
        'eq',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="1">0</Field></Filter></Filters>',
        'equal',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="2">0</Field></Filter></Filters>',
        '>=',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="3">0</Field></Filter></Filters>',
        '<=',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="4">0</Field></Filter></Filters>',
        '>',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="5">0</Field></Filter></Filters>',
        '<',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="6">0</Field></Filter></Filters>',
        'contains',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="7">0</Field></Filter></Filters>',
        '!=',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="8" /></Filter></Filters>',
        'empty',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="9" /></Filter></Filters>',
        'not empty',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="10">0</Field></Filter></Filters>',
        'starts with',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="11">0</Field></Filter></Filters>',
        'contains not',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="12">0</Field></Filter></Filters>',
        'starts not with',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="13">0</Field></Filter></Filters>',
        'ends with',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="14">0</Field></Filter></Filters>',
        'ends not with',
      ],
      [
        '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="15">0</Field></Filter></Filters>',
        'quick',
      ],
    ];
  }

  /**
   * Tests if the right output is generated using two filters.
   */
  public function testTwoFilters() {
    $container = new FilterContainer();
    $container
      ->filter('item_id', 0)
      ->filter('status', 1);
    $expected = '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="1">0</Field><Field FieldId="status" OperatorType="1">1</Field></Filter></Filters>';
    $this->assertXmlStringEqualsXmlString($expected, $container->compile());
  }

  /**
   * Tests if the right output is generated using two filter groups.
   */
  public function testTwoFilterGroups() {
    $container = new FilterContainer();
    $container->group()
      ->filter('item_id', 0);
    $container->group()
      ->filter('item_id', 456);
    $expected = '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="1">0</Field></Filter><Filter FilterId="Filter 2"><Field FieldId="item_id" OperatorType="1">456</Field></Filter></Filters>';
    $this->assertXmlStringEqualsXmlString($expected, $container->compile());
  }

  /**
   * Tests if new filters are applied against the latest filter group.
   */
  public function testAddFilterAfterGroup() {
    $container = new FilterContainer();
    $container->group()
      ->filter('item_id', 0);
    $container->group();
    $container->filter('item_id', 456);
    $expected = '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="1">0</Field></Filter><Filter FilterId="Filter 2"><Field FieldId="item_id" OperatorType="1">456</Field></Filter></Filters>';
    $this->assertXmlStringEqualsXmlString($expected, $container->compile());
  }

  /**
   * Tests if empty groups are ignored in output generation.
   */
  public function testEmptyGroup() {
    $container = new FilterContainer();
    $container->group()
      ->filter('item_id', 0);
    $container->group();
    $expected = '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="1">0</Field></Filter></Filters>';
    $this->assertXmlStringEqualsXmlString($expected, $container->compile());
  }

  /**
   * Tests removing filters.
   */
  public function testRemoveFilter() {
    $container = new FilterContainer();
    $container
      ->filter('item_id', 0)
      ->filter('status', 1);
    // Remove the second created filter.
    $container->removeFilter(1);
    $expected = '<Filters><Filter FilterId="Filter 1"><Field FieldId="item_id" OperatorType="1">0</Field></Filter></Filters>';
    $this->assertXmlStringEqualsXmlString($expected, $container->compile());
  }

}

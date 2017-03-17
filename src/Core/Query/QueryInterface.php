<?php

namespace Afas\Core\Query;

interface QueryInterface {
  /**
   * Runs the query against Profit.
   *
   * @return \Afas\Core\Result\ResultInterface|null
   *   A prepared statement, or NULL if the query is not valid.
   */
  public function execute();
}

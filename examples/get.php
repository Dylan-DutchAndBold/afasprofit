<?php

/**
 * @file
 * Psuedo-code for using a get-connector.
 */

use Afas\Core\Server;

// Bootstrap.
require_once __DIR__ . '/../includes/bootstrap.php';

// Create AfasServer.
$server = new Server();

$products = $server->get('KKB_Studiedagen')
  ->filter('model', '2612')
  ->execute()
  ->asArray();

print '<pre>';
print_r($products);

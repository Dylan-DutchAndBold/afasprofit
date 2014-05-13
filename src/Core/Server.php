<?php

/**
 * @file
 * Definition of Afas\Core\Server.
 */

namespace Afas\Core;

class Server implements ServerInterface {
  // --------------------------------------------------------------
  // PROPERTIES
  // --------------------------------------------------------------

  /**
   * The base url of the server.
   *
   * @var string
   */
  private $baseUrl;

  /**
   * The uri of the server.
   *
   * @var string
   */
  private $uri;

  /**
   * The Profit environment to use.
   *
   * @var string
   */
  private $environmentId;

  /**
   * The username to use to login to Profit.
   *
   * @var string
   */
  private $userId;

  /**
   * The password to use to login to Profit.
   *
   * @var string
   */
  private $password;

  // --------------------------------------------------------------
  // CONSTRUCT
  // --------------------------------------------------------------

  /**
   * Server object constructor.
   *
   * @param string $environment_id
   *   The Profit environment to use.
   * @param string $user_id
   *   The username to use to login to Profit.
   * @param string $password
   *   The password to use to login to Profit.
   *
   * @return \Afas\Core\Server
   */
  public function __construct($environment_id, $user_id, $password) {
    $this->baseUrl = 'https://profitweb.afasonline.nl/profitservices';
    $this->uri = 'urn:Afas.Profit.Services';
    $this->environmentId = $environment_id;
    $this->userId = $user_id;
    $this->password = $password;
  }

  // --------------------------------------------------------------
  // GETTERS
  // --------------------------------------------------------------

  /**
   * Implements ServerInterface::getBaseUrl().
   */
  public function getBaseUrl() {
    return $this->baseUrl;
  }

  /**
   * Implements ServerInterface::getUri().
   */
  public function getUri() {
    return $this->uri;
  }

  /**
   * Implements ServerInterface::getEnvironmentId().
   */
  public function getEnvironmentId() {
    return $this->environmentId;
  }

  /**
   * Implements ServerInterface::getUserId().
   */
  public function getUserId() {
    return $this->userId;
  }

  /**
   * Implements ServerInterface::getPassword().
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * Implements ServerInterface::getLogonAs().
   */
  public function getLogonAs() {
    return '';
  }
}

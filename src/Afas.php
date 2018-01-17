<?php

namespace Afas;

use Afas\Core\Soap\DefaultSoapClientFactory;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Static Service Container wrapper.
 */
class Afas {

  /**
   * The currently active container object, or NULL if not initialized yet.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerInterface|null
   */
  protected static $container;

  /**
   * Sets a new global container.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   A new container instance to replace the current.
   */
  public static function setContainer(ContainerInterface $container) {
    static::$container = $container;
  }

  /**
   * Unsets the global container.
   */
  public static function unsetContainer() {
    static::$container = NULL;
  }

  /**
   * Returns the currently active global container.
   *
   * @return \Symfony\Component\DependencyInjection\ContainerInterface
   */
  public static function getContainer() {
    if (static::$container === NULL) {
      static::setDefaultContainer();
    }
    return static::$container;
  }

  /**
   * Returns if the container has been initialized.
   *
   * @return bool
   *   TRUE if the container has been initialized, FALSE otherwise.
   */
  public static function hasContainer() {
    return static::$container !== NULL;
  }

  /**
   * Retrieves a service from the container.
   *
   * Use this method if the desired service is not one of those with a dedicated
   * accessor method below. If it is listed below, those methods are preferred
   * as they can return useful type hints.
   *
   * @param string $id
   *   The ID of the service to retrieve.
   *
   * @return mixed
   *   The specified service.
   */
  public static function service($id) {
    return static::getContainer()->get($id);
  }

  /**
   * Instantiates a container with default services.
   */
  public static function setDefaultContainer() {
    $container = new Container();
    $container->set('afas_soap_client_factory', new DefaultSoapClientFactory());

    static::setContainer($container);
  }

}

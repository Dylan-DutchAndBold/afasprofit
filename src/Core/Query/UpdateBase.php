<?php

namespace Afas\Core\Query;

use Afas\Core\Connector\UpdateConnector;
use Afas\Core\Entity\EntityContainer;
use Afas\Core\ServerInterface;

/**
 * Inserting new data into Profit.
 */
class UpdateBase extends Query implements UpdateBaseInterface {

  /**
   * The name of the UpdateConnector.
   *
   * @var string
   */
  protected $connectorId;

  /**
   * An entity container.
   *
   * @var \Afas\Core\Entity\EntityContainerInterface
   */
  protected $entityContainer;

  // --------------------------------------------------------------
  // CONSTRUCT
  // --------------------------------------------------------------

  /**
   * Constructs a new UpdateBase object.
   *
   * @param \Afas\Core\ServerInterface $server
   *   The server to send data to.
   * @param string $connector_id
   *   The name of the UpdateConnector.
   * @param array $data
   *   The data to update.
   * @param array $attribute_keys
   *   (optional) The keys belonging to attributes.
   */
  public function __construct(ServerInterface $server, $connector_id, array &$data, array $attribute_keys = []) {
    parent::__construct($server);
    $this->connectorId = $connector_id;
    $this->entityContainer = new EntityContainer($connector_id);

    if (!empty($attribute_keys)) {
      if ($this->isAssociative($data)) {
        $this->convertAttributes($data, $attribute_keys);
      }
      else {
        foreach ($data as &$subdata) {
          $this->convertAttributes($subdata, $attribute_keys);
        }
      }
    }
  }

  /**
   * Sets attributes on item array, if available.
   *
   * @param array $item
   *   The item to convert attributes for.
   * @param array $attribute_keys
   *   The attribute keys.
   */
  protected function convertAttributes(array &$item, array $attribute_keys) {
    foreach ($attribute_keys as $attribute_key) {
      if (isset($item[$attribute_key])) {
        $item['@attributes'][$attribute_key] = $item[$attribute_key];
        unset($item[$attribute_key]);
      }
    }
  }

  // --------------------------------------------------------------
  // ACTION
  // --------------------------------------------------------------

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $connector = new UpdateConnector($this->getClient(), $this->server, $this->connectorId);
    $connector->setEntityContainer($this->entityContainer);
    $connector->execute();
    return $connector->getResult();
  }

  // --------------------------------------------------------------
  // GETTERS
  // --------------------------------------------------------------

  /**
   * {@inheritdoc}
   */
  public function getEntityContainer() {
    return $this->entityContainer;
  }

  /**
   * Checks if an array is associative.
   */
  protected function isAssociative($arr) {
    foreach ($arr as $key => $value) {
      if (is_string($key)) {
        return TRUE;
      }
    }
    return FALSE;
  }

}

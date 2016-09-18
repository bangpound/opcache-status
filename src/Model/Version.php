<?php

namespace OpCache\Model;

/**
 * Class Version.
 */
class Version
{
    /**
   * @var string
   */
  private $version;

  /**
   * @var string
   */
  private $opcacheProductName;

  /**
   * Version constructor.
   *
   * @param $version string
   * @param $opcacheProductName string
   */
  public function __construct($version, $opcacheProductName)
  {
      $this->version = $version;
      $this->opcacheProductName = $opcacheProductName;
  }

  /**
   * @return string
   */
  public function getVersion()
  {
      return $this->version;
  }

  /**
   * @param string $version
   */
  public function setVersion($version)
  {
      $this->version = $version;
  }

  /**
   * @return string
   */
  public function getOpcacheProductName()
  {
      return $this->opcacheProductName;
  }

  /**
   * @param string $name
   */
  public function setOpcacheProductName($name)
  {
      $this->opcacheProductName = $name;
  }
}

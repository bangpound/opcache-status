<?php

namespace OpCache\Model;

class Script
{
    /**
   * @var string
   */
  private $fullPath;

  /**
   * @var int
   */
  private $hits;

  /**
   * @var int
   */
  private $memoryConsumption;

  /**
   * @var \DateTimeInterface
   */
  private $lastUsed;

  /**
   * @var int
   */
  private $timestamp;

    public function __construct($fullPath, $hits, $memoryConsumption, $lastUsed, $timestamp)
    {
        $this->fullPath = $fullPath;
        $this->hits = $hits;
        $this->memoryConsumption = $memoryConsumption;
        $this->lastUsed = $lastUsed;
        $this->timesamp = $timestamp;
    }

  /**
   * @return string
   */
  public function getFullPath()
  {
      return $this->fullPath;
  }

  /**
   * @return int
   */
  public function getHits()
  {
      return $this->hits;
  }

  /**
   * @return int
   */
  public function getMemoryConsumption()
  {
      return $this->memoryConsumption;
  }

  /**
   * @return \DateTimeInterface
   */
  public function getLastUsed()
  {
      return $this->lastUsed;
  }

  /**
   * @param \DateTimeInterface $lastUsed
   */
  public function setLastUsed($lastUsed)
  {
      $this->lastUsed = $lastUsed;
  }

  /**
   * @return int
   */
  public function getTimestamp()
  {
      return $this->timestamp;
  }

  /**
   * @param int $timestamp
   */
  public function setTimestamp($timestamp)
  {
      $this->timestamp = $timestamp;
  }
}

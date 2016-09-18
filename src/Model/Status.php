<?php

namespace OpCache\Model;

class Status
{
    /**
   * @var bool
   */
  private $opcacheEnabled;

  /**
   * @var bool
   */
  private $cacheFull;

  /**
   * @var bool
   */
  private $restartPending;

  /**
   * @var bool
   */
  private $restartInProgress;

  /**
   * @var \OpCache\Model\MemoryUsage
   */
  private $memoryUsage;

  /**
   * @var \OpCache\Model\InternedStringsUsage
   */
  private $internedStringsUsage;

  /**
   * @var \OpCache\Model\Statistics
   */
  private $statistics;

  /**
   * Status constructor.
   *
   * @param $opcacheEnabled
   * @param $cacheFull
   * @param $restartPending
   * @param $restartInProgress
   */
  public function __construct(
    $opcacheEnabled,
    $cacheFull,
    $restartPending,
    $restartInProgress,
    MemoryUsage $memoryUsage,
    InternedStringsUsage $internedStringsUsage,
    Statistics $statistics
  ) {
      $this->opcacheEnabled = $opcacheEnabled;
      $this->cacheFull = $cacheFull;
      $this->restartPending = $restartPending;
      $this->restartInProgress = $restartInProgress;
      $this->memoryUsage = $memoryUsage;
      $this->internedStringsUsage = $internedStringsUsage;
      $this->statistics = $statistics;
  }

  /**
   * @return bool
   */
  public function isOpcacheEnabled()
  {
      return $this->opcacheEnabled;
  }

  /**
   * @param bool $opcacheEnabled
   */
  public function setOpcacheEnabled($opcacheEnabled)
  {
      $this->opcacheEnabled = $opcacheEnabled;
  }

  /**
   * @return bool
   */
  public function isCacheFull()
  {
      return $this->cacheFull;
  }

  /**
   * @param bool $cacheFull
   */
  public function setCacheFull($cacheFull)
  {
      $this->cacheFull = $cacheFull;
  }

  /**
   * @return bool
   */
  public function isRestartPending()
  {
      return $this->restartPending;
  }

  /**
   * @param bool $restartPending
   */
  public function setRestartPending($restartPending)
  {
      $this->restartPending = $restartPending;
  }

  /**
   * @return bool
   */
  public function isRestartInProgress()
  {
      return $this->restartInProgress;
  }

  /**
   * @param bool $restartInProgress
   */
  public function setRestartInProgress($restartInProgress)
  {
      $this->restartInProgress = $restartInProgress;
  }
}

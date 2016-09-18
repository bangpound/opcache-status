<?php

namespace OpCache\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

class Configuration
{
    /**
   * @var Directive[]
   */
  private $directives;

  /**
   * @var Version
   */
  private $version;

  /**
   * @var Blacklist[]
   */
  private $blacklist;

    public function __construct()
    {
        $this->directives = new ArrayCollection();
        $this->blacklist = new ArrayCollection();
    }

  /**
   * @return Directive[]
   */
  public function getDirectives()
  {
      return $this->directives;
  }

  /**
   * @param Directive[] $directives
   */
  public function setDirectives($directives)
  {
      $this->directives = $directives;
  }

    public function addDirective(Directive $directive)
    {
        $this->directives->add($directive);
    }

  /**
   * @return \OpCache\Model\Version
   */
  public function getVersion()
  {
      return $this->version;
  }

  /**
   * @param \OpCache\Model\Version $version
   */
  public function setVersion(Version $version)
  {
      $this->version = $version;
  }

  /**
   * @return \OpCache\Model\Blacklist[]
   */
  public function getBlacklist()
  {
      return $this->blacklist;
  }

  /**
   * @param \OpCache\Model\Blacklist[] $blacklist
   */
  public function setBlacklist($blacklist)
  {
      $this->blacklist = $blacklist;
  }

    public function addBlacklist(Blacklist $blacklist)
    {
        $this->blacklist->add($blacklist);
    }
}

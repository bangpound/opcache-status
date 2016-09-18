<?php

namespace OpCache;


use Silex\Provider\AssetServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\SerializerServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;

class Application extends \Silex\Application
{
  public function __construct(array $values = array()) {
    parent::__construct($values);
    $this->register(new ServiceControllerServiceProvider());
    $this->register(new AssetServiceProvider());
    $this->register(new TwigServiceProvider());
    $this->register(new HttpFragmentServiceProvider());
    $this->register(new SerializerServiceProvider());

    $this['twig'] = $this->extend('twig', function (\Twig_Environment $twig, Application $app) {
      // add custom globals, filters, tags, ...

      return $twig;
    });
    $this->register(new \OpCache\ServiceProvider());
  }
}
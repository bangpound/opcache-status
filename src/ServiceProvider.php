<?php

namespace OpCache;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ServiceProvider.
 */
class ServiceProvider implements ServiceProviderInterface\ {
  /**
   * Registers services on the given container.
   *
   * This method should only be used to configure services and parameters.
   * It should not get services.
   *
   * @param Containe $pimple A container instance
   */
  public function register(Container $pimple) {

  $pimple['naming_thing'] = function () {
    return new CamelCaseToSnakeCaseNameConverter();
  };

  $pimple->extend('serializer.normalizers', function (array $normalizers, Container $c) {
    $namer = $c['naming_thing'];
    array_unshift($normalizers,
      new ObjectNormalizer(NULL, $namer),
      new ArrayDenormalizer(NULL, $namer),
      new DateTimeNormalizer(NULL, $namer),
      new PropertyAccessor());
    return $normalizers;
  });

}
}

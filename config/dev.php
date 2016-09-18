<?php

use Silex\Provider\MonologServiceProvider;
use Silex\Provider\VarDumperServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Sorien\Provider\PimpleDumpProvider;

// include the prod configuration
require __DIR__.'/prod.php';

// enable the debug mode
$app['debug'] = true;

//$app->register(new MonologServiceProvider(), array(
//    'monolog.logfile' => __DIR__.'/../var/logs/silex_dev.log',
//));

//$app->register(new VarDumperServiceProvider());

$app->register(new WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => __DIR__.'/../var/cache/profiler',
));

//$app['pimpledump.dumper'] = function () {
//  return new PimpleDumpProvider();
//};
//$app->register($app['pimpledump.dumper'], [
//  'pimpledump.output_dir' => __DIR__.'/../var/cache',
//]);
//
//if ($app['debug']) {
//  $app['pimpledump.dumper']->dump($app);
//}

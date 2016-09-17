<?php

use OpCache\DataModel;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Tthis looks like it is optional and about localization.
define('THOUSAND_SEPARATOR',true);

require 'vendor/autoload.php';

$app = new Application([
  'debug' => true,
]);
$app->register(new TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/res/templates',
));
$app->register(new \Silex\Provider\VarDumperServiceProvider());




$app->get('/', function (Request $request, Application $app) {
  $dataModel = new DataModel();
  dump($dataModel);

  $output = $app['twig']->render('index.html.twig', [
    'title' => $dataModel->getPageTitle(),
    'script_status_rows' => $dataModel->getScriptStatusRows(),
    'status_data_rows' => $dataModel->getStatusDataRows(),
    'config_data_rows' => $dataModel->getConfigDataRows(),
    'script_status_count' => $dataModel->getScriptStatusCount(),
    'graph_data_set_json' => $dataModel->getGraphDataSetJson(),
    'human_used_memory' => $dataModel->getHumanUsedMemory(),
    'human_free_memory' => $dataModel->getHumanFreeMemory(),
    'human_wasted_memory' => $dataModel->getHumanWastedMemory(),
    'wasted_memory_percentage' => $dataModel->getWastedMemoryPercentage(),
    'd3_scripts' => $dataModel->getD3Scripts(),
  ]);

  return new Response($output);
});


$app->run();

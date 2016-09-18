<?php

use OpCache\Model\Configuration;
use OpCache\Model\Status;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function (Application $app) {
    return $app['twig']->render('index.html.twig', array());
})
->bind('homepage')
;

$app->get('/status', function (Request $request, Application $app) {
    $data = opcache_get_status($request->query->getBoolean('scripts', true));
    $data = json_encode($data);

    $status = $app['serializer']->deserialize($data, Status::class, 'json');

    return new JsonResponse($status);
});

$app->get('/configuration', function (Request $request, Application $app) {
    $data = opcache_get_configuration();
    $data = json_encode($data);

    $config = $app['serializer']->deserialize($data, Configuration::class, 'json');

    return new JsonResponse($config);
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(['code' => $code]), $code);
});
//return array(
//  'opcache_enabled' => TRUE,
//  'cache_full' => FALSE,
//  'restart_pending' => FALSE,
//  'restart_in_progress' => FALSE,
//  'memory_usage' => array(
//    'used_memory' => '23057224',
//    'free_memory' => '111028624',
//    'wasted_memory' => '131880',
//    'current_wasted_percentage' => '0.0982582569122'
//  ),
//  'interned_strings_usage' => array(
//    'buffer_size' => '8388608',
//    'used_memory' => '970888',
//    'free_memory' => '7417720',
//    'number_of_strings' => '15436'
//  ),
//  'opcache_statistics' => array(
//    'num_cached_scripts' => '382',
//    'num_cached_keys' => '715',
//    'max_cached_keys' => '7963',
//    'hits' => '8757',
//    'start_time' => '1474165076',
//    'last_restart_time' => '0',
//    'oom_restarts' => '0',
//    'hash_restarts' => '0',
//    'manual_restarts' => '0',
//    'misses' => '414',
//    'blacklist_misses' => '0',
//    'blacklist_miss_ratio' => '0',
//    'opcache_hit_rate' => '95.4857703631'
//  ),
//  'scripts' => array(
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/AssignName.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/AssignName.php',
//      'hits' => '0',
//      'memory_consumption' => '2448',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolver/RequestAttributeValueResolver.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolver/RequestAttributeValueResolver.php',
//      'hits' => '46',
//      'memory_consumption' => '3488',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/AutoEscape.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/AutoEscape.php',
//      'hits' => '0',
//      'memory_consumption' => '3312',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/DumpListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/DumpListener.php',
//      'hits' => '15',
//      'memory_consumption' => '4720',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Compiler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Compiler.php',
//      'hits' => '2',
//      'memory_consumption' => '18984',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/JsonEncode.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/JsonEncode.php',
//      'hits' => '8',
//      'memory_consumption' => '5328',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Unary/Neg.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Unary/Neg.php',
//      'hits' => '0',
//      'memory_consumption' => '2032',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/ec/ecd4154ba04a02238b3b5c1e6d26d7d52b7aad43f3b6be322a2b9f18afed1c4f.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/ec/ecd4154ba04a02238b3b5c1e6d26d7d52b7aad43f3b6be322a2b9f18afed1c4f.php',
//      'hits' => '9',
//      'memory_consumption' => '43192',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/FragmentHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/FragmentHandler.php',
//      'hits' => '46',
//      'memory_consumption' => '8896',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/HttpFragmentServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/HttpFragmentServiceProvider.php',
//      'hits' => '50',
//      'memory_consumption' => '9176',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Exception/UnexpectedValueException.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Exception/UnexpectedValueException.php',
//      'hits' => '2',
//      'memory_consumption' => '1736',
//      'last_used' => 'Sat Sep 17 22:13:56 2016',
//      'last_used_timestamp' => '1474168436',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Or.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Or.php',
//      'hits' => '0',
//      'memory_consumption' => '2016',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParserBrokerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParserBrokerInterface.php',
//      'hits' => '2',
//      'memory_consumption' => '3416',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Cloner/VarCloner.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Cloner/VarCloner.php',
//      'hits' => '39',
//      'memory_consumption' => '29360',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472634342'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Resources/functions/dump.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Resources/functions/dump.php',
//      'hits' => '54',
//      'memory_consumption' => '1928',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472634342'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/BlockReference.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/BlockReference.php',
//      'hits' => '2',
//      'memory_consumption' => '3496',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Loader/Chain.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Loader/Chain.php',
//      'hits' => '46',
//      'memory_consumption' => '12208',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeOutputInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeOutputInterface.php',
//      'hits' => '2',
//      'memory_consumption' => '1496',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/SerializerAwareEncoder.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/SerializerAwareEncoder.php',
//      'hits' => '8',
//      'memory_consumption' => '2304',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Lexer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Lexer.php',
//      'hits' => '2',
//      'memory_consumption' => '54520',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/GetResponseForExceptionEvent.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/GetResponseForExceptionEvent.php',
//      'hits' => '11',
//      'memory_consumption' => '4256',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/src/ServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/src/ServiceProvider.php',
//      'hits' => '16',
//      'memory_consumption' => '2608',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1474168353'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/KernelEvents.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/KernelEvents.php',
//      'hits' => '47',
//      'memory_consumption' => '1800',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/84/8433a6b3d9b24cf408982c992444b59e00dda9dc3104a140dcd6b5645135c47d.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/84/8433a6b3d9b24cf408982c992444b59e00dda9dc3104a140dcd6b5645135c47d.php',
//      'hits' => '9',
//      'memory_consumption' => '4360',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/EventDispatcher.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/EventDispatcher.php',
//      'hits' => '48',
//      'memory_consumption' => '16344',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1468925157'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/EventSubscriberInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/EventSubscriberInterface.php',
//      'hits' => '48',
//      'memory_consumption' => '3024',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1468925157'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Name.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Name.php',
//      'hits' => '0',
//      'memory_consumption' => '7952',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/a5/a54b567815a5421ed9cf76e5cb3fe2129b3b48f70db2d3d45f211959800434dc.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/a5/a54b567815a5421ed9cf76e5cb3fe2129b3b48f70db2d3d45f211959800434dc.php',
//      'hits' => '9',
//      'memory_consumption' => '16672',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/CompiledRoute.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/CompiledRoute.php',
//      'hits' => '29',
//      'memory_consumption' => '11136',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/Debug.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/Debug.php',
//      'hits' => '41',
//      'memory_consumption' => '5104',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Formatter/FormatterInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Formatter/FormatterInterface.php',
//      'hits' => '16',
//      'memory_consumption' => '2664',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/26/26c650c7099c7ef92ba3bb2bf1f870aa506bb3d1d82f0d3ca744336b5f69011f.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/26/26c650c7099c7ef92ba3bb2bf1f870aa506bb3d1d82f0d3ca744336b5f69011f.php',
//      'hits' => '9',
//      'memory_consumption' => '4360',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/UriSigner.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/UriSigner.php',
//      'hits' => '48',
//      'memory_consumption' => '10984',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolver/DefaultValueResolver.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolver/DefaultValueResolver.php',
//      'hits' => '46',
//      'memory_consumption' => '2960',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeVisitor/Optimizer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeVisitor/Optimizer.php',
//      'hits' => '2',
//      'memory_consumption' => '22112',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/Exception/FatalErrorException.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/Exception/FatalErrorException.php',
//      'hits' => '2',
//      'memory_consumption' => '7072',
//      'last_used' => 'Sat Sep 17 21:21:16 2016',
//      'last_used_timestamp' => '1474165276',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/Routing/LazyRequestMatcher.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/Routing/LazyRequestMatcher.php',
//      'hits' => '48',
//      'memory_consumption' => '3896',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Core.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Core.php',
//      'hits' => '46',
//      'memory_consumption' => '133584',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/RouteCompilerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/RouteCompilerInterface.php',
//      'hits' => '29',
//      'memory_consumption' => '2312',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/composer/ClassLoader.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/composer/ClassLoader.php',
//      'hits' => '46',
//      'memory_consumption' => '28800',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1474165254'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/stopwatch/StopwatchPeriod.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/stopwatch/StopwatchPeriod.php',
//      'hits' => '22',
//      'memory_consumption' => '4856',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467178916'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyAccess.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyAccess.php',
//      'hits' => '18',
//      'memory_consumption' => '3104',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471266928'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Staging.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Staging.php',
//      'hits' => '46',
//      'memory_consumption' => '7880',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/Routing/RedirectableUrlMatcher.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/Routing/RedirectableUrlMatcher.php',
//      'hits' => '46',
//      'memory_consumption' => '5184',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/For.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/For.php',
//      'hits' => '0',
//      'memory_consumption' => '12280',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/config/prod.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/config/prod.php',
//      'hits' => '50',
//      'memory_consumption' => '1328',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1474153195'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/Debug/TraceableEventDispatcher.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/Debug/TraceableEventDispatcher.php',
//      'hits' => '39',
//      'memory_consumption' => '29960',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1468925157'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/TokenParser/DumpTokenParser.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/TokenParser/DumpTokenParser.php',
//      'hits' => '0',
//      'memory_consumption' => '4032',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472630853'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyPathInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyPathInterface.php',
//      'hits' => '2',
//      'memory_consumption' => '5616',
//      'last_used' => 'Sat Sep 17 22:13:56 2016',
//      'last_used_timestamp' => '1474168436',
//      'timestamp' => '1471266928'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Loader/Array.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Loader/Array.php',
//      'hits' => '46',
//      'memory_consumption' => '7024',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/SerializerAwareInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/SerializerAwareInterface.php',
//      'hits' => '18',
//      'memory_consumption' => '2048',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/ProfilerListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/ProfilerListener.php',
//      'hits' => '38',
//      'memory_consumption' => '10656',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Matcher/RedirectableUrlMatcherInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Matcher/RedirectableUrlMatcherInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '2496',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/web/index_dev.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/web/index_dev.php',
//      'hits' => '22',
//      'memory_consumption' => '2760',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1474168273'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Cloner/DumperInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Cloner/DumperInterface.php',
//      'hits' => '15',
//      'memory_consumption' => '5096',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1472634342'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/e9/e9bac705a601a374a3394c10542d1dd9bdeea72d34c848e56a2eece975daefd8.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/e9/e9bac705a601a374a3394c10542d1dd9bdeea72d34c848e56a2eece975daefd8.php',
//      'hits' => '9',
//      'memory_consumption' => '4360',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/NotEqual.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/NotEqual.php',
//      'hits' => '0',
//      'memory_consumption' => '2080',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/CompilerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/CompilerInterface.php',
//      'hits' => '2',
//      'memory_consumption' => '2592',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Profiler/Profile.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Profiler/Profile.php',
//      'hits' => '39',
//      'memory_consumption' => '14464',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Logger.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Logger.php',
//      'hits' => '18',
//      'memory_consumption' => '46344',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/ParameterBag.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/ParameterBag.php',
//      'hits' => '31',
//      'memory_consumption' => '16864',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471867879'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/EventListener/StringToResponseListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/EventListener/StringToResponseListener.php',
//      'hits' => '31',
//      'memory_consumption' => '3928',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Set.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Set.php',
//      'hits' => '0',
//      'memory_consumption' => '9264',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Filter/Default.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Filter/Default.php',
//      'hits' => '0',
//      'memory_consumption' => '5944',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/91/916daa6e777ee81e21283c27e8eb5b955702a84d5a973772e374ae1a20fc4cf7.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/91/916daa6e777ee81e21283c27e8eb5b955702a84d5a973772e374ae1a20fc4cf7.php',
//      'hits' => '4',
//      'memory_consumption' => '5408',
//      'last_used' => 'Sat Sep 17 21:59:25 2016',
//      'last_used_timestamp' => '1474167565',
//      'timestamp' => '1474165867'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/Exception/ExceptionInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/Exception/ExceptionInterface.php',
//      'hits' => '2',
//      'memory_consumption' => '1568',
//      'last_used' => 'Sat Sep 17 22:13:56 2016',
//      'last_used_timestamp' => '1474168436',
//      'timestamp' => '1471266928'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/VersionStrategy/VersionStrategyInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/VersionStrategy/VersionStrategyInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '2672',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467388800'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/DataCollector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/DataCollector.php',
//      'hits' => '39',
//      'memory_consumption' => '4144',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Test.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Test.php',
//      'hits' => '0',
//      'memory_consumption' => '4544',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Application.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Application.php',
//      'hits' => '50',
//      'memory_consumption' => '40928',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/NormalizationAwareInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/NormalizationAwareInterface.php',
//      'hits' => '8',
//      'memory_consumption' => '1736',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/42/423d9f62731d1cf5c83a5a8b4c375ae1ebf235952cd6c156fc3747e3c011175b.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/42/423d9f62731d1cf5c83a5a8b4c375ae1ebf235952cd6c156fc3747e3c011175b.php',
//      'hits' => '9',
//      'memory_consumption' => '68752',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Module.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Module.php',
//      'hits' => '2',
//      'memory_consumption' => '40064',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Dumper/AbstractDumper.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Dumper/AbstractDumper.php',
//      'hits' => '15',
//      'memory_consumption' => '14352',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1472634342'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/FatalErrorHandler/FatalErrorHandlerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/FatalErrorHandler/FatalErrorHandlerInterface.php',
//      'hits' => '2',
//      'memory_consumption' => '2456',
//      'last_used' => 'Sat Sep 17 21:21:16 2016',
//      'last_used_timestamp' => '1474165276',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/CodeExtension.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/CodeExtension.php',
//      'hits' => '39',
//      'memory_consumption' => '30016',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472630853'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Generator/ConfigurableRequirementsInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Generator/ConfigurableRequirementsInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '4032',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Sub.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Sub.php',
//      'hits' => '0',
//      'memory_consumption' => '2016',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/KernelEvent.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/KernelEvent.php',
//      'hits' => '29',
//      'memory_consumption' => '5152',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Kernel.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Kernel.php',
//      'hits' => '50',
//      'memory_consumption' => '60896',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/ExceptionHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/ExceptionHandler.php',
//      'hits' => '48',
//      'memory_consumption' => '4784',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/ExceptionDataCollector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/ExceptionDataCollector.php',
//      'hits' => '38',
//      'memory_consumption' => '6272',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Function.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Function.php',
//      'hits' => '1',
//      'memory_consumption' => '5200',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Profiler/Profiler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Profiler/Profiler.php',
//      'hits' => '38',
//      'memory_consumption' => '19240',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/DecoderInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/DecoderInterface.php',
//      'hits' => '8',
//      'memory_consumption' => '3216',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/AbstractObjectNormalizer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/AbstractObjectNormalizer.php',
//      'hits' => '18',
//      'memory_consumption' => '29744',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/65/65353fb808e5e225e34b526b3d759a0b905f86871ed0994e04c281328a25d76b.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/65/65353fb808e5e225e34b526b3d759a0b905f86871ed0994e04c281328a25d76b.php',
//      'hits' => '9',
//      'memory_consumption' => '102736',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Body.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Body.php',
//      'hits' => '2',
//      'memory_consumption' => '1568',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Add.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Add.php',
//      'hits' => '0',
//      'memory_consumption' => '2016',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/TimeDataCollector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/TimeDataCollector.php',
//      'hits' => '38',
//      'memory_consumption' => '9792',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/composer/autoload_real.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/composer/autoload_real.php',
//      'hits' => '46',
//      'memory_consumption' => '9560',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1474165254'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/FatalErrorHandler/UndefinedFunctionFatalErrorHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/FatalErrorHandler/UndefinedFunctionFatalErrorHandler.php',
//      'hits' => '2',
//      'memory_consumption' => '7560',
//      'last_used' => 'Sat Sep 17 21:21:16 2016',
//      'last_used_timestamp' => '1474165276',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Exception/HttpException.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Exception/HttpException.php',
//      'hits' => '0',
//      'memory_consumption' => '4152',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Route.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Route.php',
//      'hits' => '48',
//      'memory_consumption' => '12928',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/AjaxDataCollector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/AjaxDataCollector.php',
//      'hits' => '39',
//      'memory_consumption' => '2472',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/VersionStrategy/EmptyVersionStrategy.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/VersionStrategy/EmptyVersionStrategy.php',
//      'hits' => '46',
//      'memory_consumption' => '2552',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467388800'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Profiler/Node/EnterProfile.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Profiler/Node/EnterProfile.php',
//      'hits' => '0',
//      'memory_consumption' => '5152',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Exception/ResourceNotFoundException.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Exception/ResourceNotFoundException.php',
//      'hits' => '0',
//      'memory_consumption' => '1816',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/HttpKernelExtension.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/HttpKernelExtension.php',
//      'hits' => '46',
//      'memory_consumption' => '7656',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472630853'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/FatalErrorHandler/ClassNotFoundFatalErrorHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/FatalErrorHandler/ClassNotFoundFatalErrorHandler.php',
//      'hits' => '2',
//      'memory_consumption' => '19488',
//      'last_used' => 'Sat Sep 17 21:21:16 2016',
//      'last_used_timestamp' => '1474165276',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Macro.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Macro.php',
//      'hits' => '2',
//      'memory_consumption' => '6640',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/AssetExtension.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/AssetExtension.php',
//      'hits' => '46',
//      'memory_consumption' => '5400',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472630853'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/XmlEncoder.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/XmlEncoder.php',
//      'hits' => '8',
//      'memory_consumption' => '42728',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/ServerBag.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/ServerBag.php',
//      'hits' => '31',
//      'memory_consumption' => '7440',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471867879'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/ChainDecoder.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/ChainDecoder.php',
//      'hits' => '8',
//      'memory_consumption' => '6048',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/ParserInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/ParserInterface.php',
//      'hits' => '2',
//      'memory_consumption' => '2288',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Test/Defined.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Test/Defined.php',
//      'hits' => '0',
//      'memory_consumption' => '5768',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/ExceptionListenerWrapper.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/ExceptionListenerWrapper.php',
//      'hits' => '31',
//      'memory_consumption' => '9024',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/RoutingExtension.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/RoutingExtension.php',
//      'hits' => '46',
//      'memory_consumption' => '9320',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472630853'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Exception/RuntimeException.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Exception/RuntimeException.php',
//      'hits' => '5',
//      'memory_consumption' => '1664',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/web/index.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/web/index.php',
//      'hits' => '8',
//      'memory_consumption' => '1192',
//      'last_used' => 'Sat Sep 17 21:59:37 2016',
//      'last_used_timestamp' => '1474167577',
//      'timestamp' => '1474165129'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Text.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Text.php',
//      'hits' => '2',
//      'memory_consumption' => '3504',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Exception/ExceptionInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Exception/ExceptionInterface.php',
//      'hits' => '0',
//      'memory_consumption' => '1480',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/EventListener/ConverterListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/EventListener/ConverterListener.php',
//      'hits' => '31',
//      'memory_consumption' => '5368',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Import.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Import.php',
//      'hits' => '0',
//      'memory_consumption' => '5720',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/FingersCrossed/ActivationStrategyInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/FingersCrossed/ActivationStrategyInterface.php',
//      'hits' => '16',
//      'memory_consumption' => '2176',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node.php',
//      'hits' => '2',
//      'memory_consumption' => '20280',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/RouteCompiler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/RouteCompiler.php',
//      'hits' => '29',
//      'memory_consumption' => '21120',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/35/35b924b59a4878ca7ec1e4d05fdf85fd4b329e2bdaace83eb0dbfc8866dbe7fe.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/35/35b924b59a4878ca7ec1e4d05fdf85fd4b329e2bdaace83eb0dbfc8866dbe7fe.php',
//      'hits' => '9',
//      'memory_consumption' => '4360',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/AutoEscape.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/AutoEscape.php',
//      'hits' => '2',
//      'memory_consumption' => '7360',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/Response.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/Response.php',
//      'hits' => '28',
//      'memory_consumption' => '82288',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471867879'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/SimpleFilter.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/SimpleFilter.php',
//      'hits' => '2',
//      'memory_consumption' => '9056',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/ForLoop.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/ForLoop.php',
//      'hits' => '0',
//      'memory_consumption' => '4880',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/EventDataCollector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/EventDataCollector.php',
//      'hits' => '38',
//      'memory_consumption' => '7032',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary.php',
//      'hits' => '0',
//      'memory_consumption' => '4248',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/LexerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/LexerInterface.php',
//      'hits' => '2',
//      'memory_consumption' => '2392',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParserInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParserInterface.php',
//      'hits' => '2',
//      'memory_consumption' => '3088',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentValueResolverInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentValueResolverInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '2928',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Formatter/LineFormatter.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Formatter/LineFormatter.php',
//      'hits' => '16',
//      'memory_consumption' => '17176',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/Debug/TraceableEventDispatcherInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/Debug/TraceableEventDispatcherInterface.php',
//      'hits' => '39',
//      'memory_consumption' => '2512',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1468925157'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Import.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Import.php',
//      'hits' => '2',
//      'memory_consumption' => '4616',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/RequestDataCollector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/RequestDataCollector.php',
//      'hits' => '38',
//      'memory_consumption' => '38008',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/JsonDecode.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/JsonDecode.php',
//      'hits' => '8',
//      'memory_consumption' => '7688',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Generator/UrlGeneratorInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Generator/UrlGeneratorInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '4584',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/83/8385fec04f383a74c3dbe570c09bd3c61d8c7f7bb9a481ffe940bdcd7b8b01b7.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/83/8385fec04f383a74c3dbe570c09bd3c61d8c7f7bb9a481ffe940bdcd7b8b01b7.php',
//      'hits' => '10',
//      'memory_consumption' => '14056',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168151'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/GetResponseEvent.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/GetResponseEvent.php',
//      'hits' => '29',
//      'memory_consumption' => '3696',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Profiler/Node/LeaveProfile.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Profiler/Node/LeaveProfile.php',
//      'hits' => '0',
//      'memory_consumption' => '3440',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/FragmentListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/FragmentListener.php',
//      'hits' => '48',
//      'memory_consumption' => '8608',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Test/Sameas.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Test/Sameas.php',
//      'hits' => '0',
//      'memory_consumption' => '3128',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/Exception/FlattenException.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/Exception/FlattenException.php',
//      'hits' => '40',
//      'memory_consumption' => '24344',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Profiler/ProfilerStorageInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Profiler/ProfilerStorageInterface.php',
//      'hits' => '38',
//      'memory_consumption' => '4272',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/TestHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/TestHandler.php',
//      'hits' => '16',
//      'memory_consumption' => '13928',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/bb/bb6c0fec3068805f3d0001cacbedb98256b1a8a724034eab1fe8eed1596dde8b.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/bb/bb6c0fec3068805f3d0001cacbedb98256b1a8a724034eab1fe8eed1596dde8b.php',
//      'hits' => '9',
//      'memory_consumption' => '4360',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/HttpKernelServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/HttpKernelServiceProvider.php',
//      'hits' => '50',
//      'memory_consumption' => '10672',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/SerializerAwareNormalizer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/SerializerAwareNormalizer.php',
//      'hits' => '18',
//      'memory_consumption' => '1848',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Escaper.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Escaper.php',
//      'hits' => '46',
//      'memory_consumption' => '7088',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression.php',
//      'hits' => '2',
//      'memory_consumption' => '1608',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/polyfill-mbstring/bootstrap.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/polyfill-mbstring/bootstrap.php',
//      'hits' => '54',
//      'memory_consumption' => '22688',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1463581606'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Cache/Filesystem.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Cache/Filesystem.php',
//      'hits' => '46',
//      'memory_consumption' => '9184',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/a7/a7b048f2395efff946464a8f9affb136c14a012a371754fcec0d5cf8ffe27f06.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/a7/a7b048f2395efff946464a8f9affb136c14a012a371754fcec0d5cf8ffe27f06.php',
//      'hits' => '5',
//      'memory_consumption' => '6440',
//      'last_used' => 'Sat Sep 17 21:59:37 2016',
//      'last_used_timestamp' => '1474167577',
//      'timestamp' => '1474165867'
//    ),
//    '/Applications/MAMP/bin/mamp/php/functions.php' => array(
//      'full_path' => '/Applications/MAMP/bin/mamp/php/functions.php',
//      'hits' => '0',
//      'memory_consumption' => '22896',
//      'last_used' => 'Sat Sep 17 21:59:17 2016',
//      'last_used_timestamp' => '1474167557',
//      'timestamp' => '1472133521'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Matcher/UrlMatcher.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Matcher/UrlMatcher.php',
//      'hits' => '46',
//      'memory_consumption' => '18528',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/In.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/In.php',
//      'hits' => '0',
//      'memory_consumption' => '3296',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyPath.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyPath.php',
//      'hits' => '2',
//      'memory_consumption' => '14144',
//      'last_used' => 'Sat Sep 17 22:13:56 2016',
//      'last_used_timestamp' => '1474168436',
//      'timestamp' => '1471266928'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/ExceptionHandlerServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/ExceptionHandlerServiceProvider.php',
//      'hits' => '50',
//      'memory_consumption' => '3472',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParserBroker.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParserBroker.php',
//      'hits' => '2',
//      'memory_consumption' => '11096',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Block.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Block.php',
//      'hits' => '2',
//      'memory_consumption' => '8648',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/CacheInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/CacheInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '4032',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/ea/ead1d0ae5f9493d2eae9db46e3f3ad07b5ae09fc8638b2d2c36931386e4942e1.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/ea/ead1d0ae5f9493d2eae9db46e3f3ad07b5ae09fc8638b2d2c36931386e4942e1.php',
//      'hits' => '9',
//      'memory_consumption' => '67224',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/sorien/silex-pimple-dumper/src/PimpleDumpProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/sorien/silex-pimple-dumper/src/PimpleDumpProvider.php',
//      'hits' => '16',
//      'memory_consumption' => '16720',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1464693309'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/LoaderInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/LoaderInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '3624',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/EncoderInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/EncoderInterface.php',
//      'hits' => '8',
//      'memory_consumption' => '2952',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/LoggerDataCollector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/LoggerDataCollector.php',
//      'hits' => '38',
//      'memory_consumption' => '17136',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/Profiler/TemplateManager.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/Profiler/TemplateManager.php',
//      'hits' => '10',
//      'memory_consumption' => '8848',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472782372'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/ExtensionInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/ExtensionInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '6000',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Route.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Route.php',
//      'hits' => '48',
//      'memory_consumption' => '35728',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/Context/RequestStackContext.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/Context/RequestStackContext.php',
//      'hits' => '46',
//      'memory_consumption' => '3640',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467388800'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/GreaterEqual.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/GreaterEqual.php',
//      'hits' => '0',
//      'memory_consumption' => '2080',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/ExpressionParser.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/ExpressionParser.php',
//      'hits' => '2',
//      'memory_consumption' => '77520',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/EventListener/LogListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/EventListener/LogListener.php',
//      'hits' => '15',
//      'memory_consumption' => '10768',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/EsiFragmentRenderer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/EsiFragmentRenderer.php',
//      'hits' => '16',
//      'memory_consumption' => '2016',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/TwigServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/TwigServiceProvider.php',
//      'hits' => '50',
//      'memory_consumption' => '14424',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/Request.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/Request.php',
//      'hits' => '41',
//      'memory_consumption' => '155608',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471867879'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Log/DebugLoggerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Log/DebugLoggerInterface.php',
//      'hits' => '18',
//      'memory_consumption' => '2544',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Exception/NotFoundHttpException.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Exception/NotFoundHttpException.php',
//      'hits' => '0',
//      'memory_consumption' => '2840',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/autoload.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/autoload.php',
//      'hits' => '46',
//      'memory_consumption' => '1152',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1474165254'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Loader/Filesystem.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Loader/Filesystem.php',
//      'hits' => '46',
//      'memory_consumption' => '21424',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolver/VariadicValueResolver.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolver/VariadicValueResolver.php',
//      'hits' => '46',
//      'memory_consumption' => '4440',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Spaceless.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Spaceless.php',
//      'hits' => '2',
//      'memory_consumption' => '4272',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/doctrine/collections/lib/Doctrine/Common/Collections/Collection.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/doctrine/collections/lib/Doctrine/Common/Collections/Collection.php',
//      'hits' => '2',
//      'memory_consumption' => '17032',
//      'last_used' => 'Sat Sep 17 22:13:56 2016',
//      'last_used_timestamp' => '1474168436',
//      'timestamp' => '1429050118'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Profiler/NodeVisitor/Profiler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Profiler/NodeVisitor/Profiler.php',
//      'hits' => '0',
//      'memory_consumption' => '8576',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Generator/UrlGenerator.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Generator/UrlGenerator.php',
//      'hits' => '46',
//      'memory_consumption' => '29472',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/ControllerMetadata/ArgumentMetadataFactoryInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/ControllerMetadata/ArgumentMetadataFactoryInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '2136',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Equal.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Equal.php',
//      'hits' => '0',
//      'memory_consumption' => '2016',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Macro.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Macro.php',
//      'hits' => '0',
//      'memory_consumption' => '10744',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/Event.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/Event.php',
//      'hits' => '29',
//      'memory_consumption' => '3408',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1468925157'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenStream.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenStream.php',
//      'hits' => '2',
//      'memory_consumption' => '13192',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/web-profiler/Silex/Provider/WebProfilerServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/web-profiler/Silex/Provider/WebProfilerServiceProvider.php',
//      'hits' => '39',
//      'memory_consumption' => '50200',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471973908'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/src/Model/Status.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/src/Model/Status.php',
//      'hits' => '5',
//      'memory_consumption' => '7400',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474158982'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Environment.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Environment.php',
//      'hits' => '46',
//      'memory_consumption' => '103576',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/FingersCrossedHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/FingersCrossedHandler.php',
//      'hits' => '16',
//      'memory_consumption' => '14296',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/For.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/For.php',
//      'hits' => '2',
//      'memory_consumption' => '14424',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/HttpKernel.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/HttpKernel.php',
//      'hits' => '46',
//      'memory_consumption' => '25440',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/doctrine/collections/lib/Doctrine/Common/Collections/Selectable.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/doctrine/collections/lib/Doctrine/Common/Collections/Selectable.php',
//      'hits' => '2',
//      'memory_consumption' => '2784',
//      'last_used' => 'Sat Sep 17 22:13:56 2016',
//      'last_used_timestamp' => '1474168436',
//      'timestamp' => '1429050118'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/Twig/WebProfilerExtension.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/Twig/WebProfilerExtension.php',
//      'hits' => '39',
//      'memory_consumption' => '3664',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472782372'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/BlockReference.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/BlockReference.php',
//      'hits' => '0',
//      'memory_consumption' => '4704',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/2d/2db1d6507e008b6a91ea1a3a19aeb32d593f757766d02ada4a4b0d265ef60718.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/2d/2db1d6507e008b6a91ea1a3a19aeb32d593f757766d02ada4a4b0d265ef60718.php',
//      'hits' => '9',
//      'memory_consumption' => '12792',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/DebugClassLoader.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/DebugClassLoader.php',
//      'hits' => '41',
//      'memory_consumption' => '34424',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/AddRequestFormatsListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/AddRequestFormatsListener.php',
//      'hits' => '1',
//      'memory_consumption' => '3864',
//      'last_used' => 'Sat Sep 17 21:19:11 2016',
//      'last_used_timestamp' => '1474165151',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/AppVariable.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/AppVariable.php',
//      'hits' => '46',
//      'memory_consumption' => '8456',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472630853'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/EventDispatcherInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/EventDispatcherInterface.php',
//      'hits' => '48',
//      'memory_consumption' => '7360',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1468925157'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/EventListener/WebDebugToolbarListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/EventListener/WebDebugToolbarListener.php',
//      'hits' => '39',
//      'memory_consumption' => '14504',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472782372'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/c0/c0b63a87cf8f53ece0e2f641fed250e914226c122ea0d52a9b6a01887826b314.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/c0/c0b63a87cf8f53ece0e2f641fed250e914226c122ea0d52a9b6a01887826b314.php',
//      'hits' => '9',
//      'memory_consumption' => '15632',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/RequestStack.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/RequestStack.php',
//      'hits' => '48',
//      'memory_consumption' => '5888',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471867879'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Dumper/DataDumperInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Dumper/DataDumperInterface.php',
//      'hits' => '15',
//      'memory_consumption' => '2032',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1472634342'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/ServiceControllerResolver.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/ServiceControllerResolver.php',
//      'hits' => '46',
//      'memory_consumption' => '4688',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Greater.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Greater.php',
//      'hits' => '0',
//      'memory_consumption' => '2080',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TemplateInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TemplateInterface.php',
//      'hits' => '28',
//      'memory_consumption' => '3512',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/DataCollectorInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/DataCollectorInterface.php',
//      'hits' => '39',
//      'memory_consumption' => '2784',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/monolog-bridge/Handler/FingersCrossed/NotFoundActivationStrategy.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/monolog-bridge/Handler/FingersCrossed/NotFoundActivationStrategy.php',
//      'hits' => '16',
//      'memory_consumption' => '4720',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1467178916'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/Debug/WrappedListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/event-dispatcher/Debug/WrappedListener.php',
//      'hits' => '22',
//      'memory_consumption' => '5776',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1468925157'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Exception/ExceptionInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Exception/ExceptionInterface.php',
//      'hits' => '8',
//      'memory_consumption' => '1480',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/ServiceControllerServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/ServiceControllerServiceProvider.php',
//      'hits' => '50',
//      'memory_consumption' => '2832',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/monolog-bridge/Handler/DebugHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/monolog-bridge/Handler/DebugHandler.php',
//      'hits' => '16',
//      'memory_consumption' => '4520',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1467178916'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Set.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Set.php',
//      'hits' => '2',
//      'memory_consumption' => '6776',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/inflector/Inflector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/inflector/Inflector.php',
//      'hits' => '2',
//      'memory_consumption' => '16064',
//      'last_used' => 'Sat Sep 17 22:13:56 2016',
//      'last_used_timestamp' => '1474168436',
//      'timestamp' => '1465385047'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/RouterDataCollector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/RouterDataCollector.php',
//      'hits' => '38',
//      'memory_consumption' => '7136',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/AbstractSurrogateFragmentRenderer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/AbstractSurrogateFragmentRenderer.php',
//      'hits' => '16',
//      'memory_consumption' => '9904',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/MonologServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/MonologServiceProvider.php',
//      'hits' => '18',
//      'memory_consumption' => '14848',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Api/ControllerProviderInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Api/ControllerProviderInterface.php',
//      'hits' => '39',
//      'memory_consumption' => '2216',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Array.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Array.php',
//      'hits' => '0',
//      'memory_consumption' => '8160',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ControllerResolver.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ControllerResolver.php',
//      'hits' => '46',
//      'memory_consumption' => '24504',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/MemoryDataCollector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/MemoryDataCollector.php',
//      'hits' => '38',
//      'memory_consumption' => '8248',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Api/EventListenerProviderInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Api/EventListenerProviderInterface.php',
//      'hits' => '50',
//      'memory_consumption' => '2048',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Flush.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Flush.php',
//      'hits' => '2',
//      'memory_consumption' => '2936',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/75/754a803cbc90d2caabcaaa6e739c7ce78d49158fc3859e69d390370cf14e3a83.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/75/754a803cbc90d2caabcaaa6e739c7ce78d49158fc3859e69d390370cf14e3a83.php',
//      'hits' => '9',
//      'memory_consumption' => '9272',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/KernelInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/KernelInterface.php',
//      'hits' => '50',
//      'memory_consumption' => '10344',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/polyfill-php70/bootstrap.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/polyfill-php70/bootstrap.php',
//      'hits' => '54',
//      'memory_consumption' => '2608',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1463581606'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/GroupHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/GroupHandler.php',
//      'hits' => '16',
//      'memory_consumption' => '7312',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/SerializerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/SerializerInterface.php',
//      'hits' => '8',
//      'memory_consumption' => '3232',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/src/Model/Configuration.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/src/Model/Configuration.php',
//      'hits' => '2',
//      'memory_consumption' => '6192',
//      'last_used' => 'Sat Sep 17 22:13:56 2016',
//      'last_used_timestamp' => '1474168436',
//      'timestamp' => '1474157193'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Exception/HttpExceptionInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Exception/HttpExceptionInterface.php',
//      'hits' => '0',
//      'memory_consumption' => '2432',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyAccessor.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyAccessor.php',
//      'hits' => '18',
//      'memory_consumption' => '79064',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471266928'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/InlineFragmentRenderer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/InlineFragmentRenderer.php',
//      'hits' => '46',
//      'memory_consumption' => '11520',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/AbstractProcessingHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/AbstractProcessingHandler.php',
//      'hits' => '16',
//      'memory_consumption' => '4560',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolverInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolverInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '2456',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Unary.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Unary.php',
//      'hits' => '0',
//      'memory_consumption' => '3480',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/FatalErrorHandler/UndefinedMethodFatalErrorHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/FatalErrorHandler/UndefinedMethodFatalErrorHandler.php',
//      'hits' => '2',
//      'memory_consumption' => '5368',
//      'last_used' => 'Sat Sep 17 21:21:16 2016',
//      'last_used_timestamp' => '1474165276',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Controller.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Controller.php',
//      'hits' => '47',
//      'memory_consumption' => '8864',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/MethodCall.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/MethodCall.php',
//      'hits' => '0',
//      'memory_consumption' => '4896',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Filter.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Filter.php',
//      'hits' => '2',
//      'memory_consumption' => '5912',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/ResponseHeaderBag.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/ResponseHeaderBag.php',
//      'hits' => '28',
//      'memory_consumption' => '24480',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471867879'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/SerializerServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/SerializerServiceProvider.php',
//      'hits' => '18',
//      'memory_consumption' => '4456',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1474159383'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/JsonEncoder.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/JsonEncoder.php',
//      'hits' => '8',
//      'memory_consumption' => '5960',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/RoutableFragmentRenderer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/RoutableFragmentRenderer.php',
//      'hits' => '46',
//      'memory_consumption' => '7120',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/Util/ValueExporter.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/Util/ValueExporter.php',
//      'hits' => '9',
//      'memory_consumption' => '8608',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/VarDumper.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/VarDumper.php',
//      'hits' => '22',
//      'memory_consumption' => '4800',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472634342'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/composer/autoload_static.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/composer/autoload_static.php',
//      'hits' => '46',
//      'memory_consumption' => '139808',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1474165254'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/monolog-bridge/Logger.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/monolog-bridge/Logger.php',
//      'hits' => '18',
//      'memory_consumption' => '3896',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1467178916'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Unary/Not.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Unary/Not.php',
//      'hits' => '0',
//      'memory_consumption' => '2032',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Block.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Block.php',
//      'hits' => '2',
//      'memory_consumption' => '4088',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/Packages.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/Packages.php',
//      'hits' => '46',
//      'memory_consumption' => '7880',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467388800'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/64/64cd069e9ec2c2199c7c7bd5a04bd4736719ca4776e13624d5048df07108c65c.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/64/64cd069e9ec2c2199c7c7bd5a04bd4736719ca4776e13624d5048df07108c65c.php',
//      'hits' => '9',
//      'memory_consumption' => '11728',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeVisitor/SafeAnalysis.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeVisitor/SafeAnalysis.php',
//      'hits' => '2',
//      'memory_consumption' => '15192',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/AbstractHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/AbstractHandler.php',
//      'hits' => '16',
//      'memory_consumption' => '11432',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/fa/fa735c25a648a05a3faa526b34d9fec857ab3aa3752e57b4d693a839b8458e3f.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/fa/fa735c25a648a05a3faa526b34d9fec857ab3aa3752e57b4d693a839b8458e3f.php',
//      'hits' => '9',
//      'memory_consumption' => '27992',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/EventListener/MiddlewareListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/EventListener/MiddlewareListener.php',
//      'hits' => '31',
//      'memory_consumption' => '8592',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/Context/ContextInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/Context/ContextInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '2416',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467388800'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/HttpKernelInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/HttpKernelInterface.php',
//      'hits' => '50',
//      'memory_consumption' => '2864',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Serializer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Serializer.php',
//      'hits' => '8',
//      'memory_consumption' => '23168',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/stopwatch/StopwatchEvent.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/stopwatch/StopwatchEvent.php',
//      'hits' => '22',
//      'memory_consumption' => '16184',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467178916'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/TerminableInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/TerminableInterface.php',
//      'hits' => '50',
//      'memory_consumption' => '2448',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/SimpleFunction.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/SimpleFunction.php',
//      'hits' => '2',
//      'memory_consumption' => '8328',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/HeaderBag.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/HeaderBag.php',
//      'hits' => '31',
//      'memory_consumption' => '26984',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471867879'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension.php',
//      'hits' => '46',
//      'memory_consumption' => '4976',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/RequestContext.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/RequestContext.php',
//      'hits' => '48',
//      'memory_consumption' => '20792',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/DataCollector/TwigDataCollector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/DataCollector/TwigDataCollector.php',
//      'hits' => '38',
//      'memory_consumption' => '12528',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472630853'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Parent.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Parent.php',
//      'hits' => '0',
//      'memory_consumption' => '4280',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeVisitor/Escaper.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeVisitor/Escaper.php',
//      'hits' => '2',
//      'memory_consumption' => '17272',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/psr/log/Psr/Log/LogLevel.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/psr/log/Psr/Log/LogLevel.php',
//      'hits' => '41',
//      'memory_consumption' => '1704',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1356090051'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/DumpExtension.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/DumpExtension.php',
//      'hits' => '39',
//      'memory_consumption' => '6600',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472630853'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Cloner/AbstractCloner.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Cloner/AbstractCloner.php',
//      'hits' => '39',
//      'memory_consumption' => '21472',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472634342'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/ControllerMetadata/ArgumentMetadataFactory.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/ControllerMetadata/ArgumentMetadataFactory.php',
//      'hits' => '46',
//      'memory_consumption' => '9320',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/be/be07f7f897a3d6097534d290083762d815aac16e178b5d6c35caaefe238c394d.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/be/be07f7f897a3d6097534d290083762d815aac16e178b5d6c35caaefe238c394d.php',
//      'hits' => '9',
//      'memory_consumption' => '4360',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/ErrorHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/ErrorHandler.php',
//      'hits' => '41',
//      'memory_consumption' => '57096',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Dumper/CliDumper.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Dumper/CliDumper.php',
//      'hits' => '15',
//      'memory_consumption' => '47496',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1472634342'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/AppArgumentValueResolver.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/AppArgumentValueResolver.php',
//      'hits' => '46',
//      'memory_consumption' => '3952',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/FingersCrossed/ErrorLevelActivationStrategy.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/FingersCrossed/ErrorLevelActivationStrategy.php',
//      'hits' => '16',
//      'memory_consumption' => '2888',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/ExistsLoaderInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/ExistsLoaderInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '2248',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/stopwatch/Section.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/stopwatch/Section.php',
//      'hits' => '39',
//      'memory_consumption' => '12296',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467178916'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/ProfilerExtension.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/ProfilerExtension.php',
//      'hits' => '39',
//      'memory_consumption' => '5032',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472630853'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/PathPackage.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/PathPackage.php',
//      'hits' => '46',
//      'memory_consumption' => '5336',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467388800'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/FileBag.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-foundation/FileBag.php',
//      'hits' => '31',
//      'memory_consumption' => '11264',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471867879'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Print.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Print.php',
//      'hits' => '1',
//      'memory_consumption' => '3640',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/FilterControllerEvent.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/FilterControllerEvent.php',
//      'hits' => '28',
//      'memory_consumption' => '3976',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/FilterResponseEvent.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/FilterResponseEvent.php',
//      'hits' => '28',
//      'memory_consumption' => '3944',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472916504'
//    ),
//    '/Applications/MAMP/bin/phpMyAdmin/config.inc.php' => array(
//      'full_path' => '/Applications/MAMP/bin/phpMyAdmin/config.inc.php',
//      'hits' => '0',
//      'memory_consumption' => '30672',
//      'last_used' => 'Sat Sep 17 21:59:17 2016',
//      'last_used_timestamp' => '1474167557',
//      'timestamp' => '1474165059'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/config/dev.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/config/dev.php',
//      'hits' => '22',
//      'memory_consumption' => '1456',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1474168245'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Optimizer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Optimizer.php',
//      'hits' => '46',
//      'memory_consumption' => '2864',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/DenormalizerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/DenormalizerInterface.php',
//      'hits' => '18',
//      'memory_consumption' => '3496',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Debug.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Debug.php',
//      'hits' => '39',
//      'memory_consumption' => '5680',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/21/219e7fb9aa98f13a43a6d91c3d68dd8f71010d35f20a9d06c74503c498d96e08.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/21/219e7fb9aa98f13a43a6d91c3d68dd8f71010d35f20a9d06c74503c498d96e08.php',
//      'hits' => '9',
//      'memory_consumption' => '10304',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/ControllerCollection.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/ControllerCollection.php',
//      'hits' => '48',
//      'memory_consumption' => '20808',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/StreamHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/StreamHandler.php',
//      'hits' => '16',
//      'memory_consumption' => '14856',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/From.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/From.php',
//      'hits' => '2',
//      'memory_consumption' => '6560',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/RequestContextAwareInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/RequestContextAwareInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '2312',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/ObjectNormalizer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/ObjectNormalizer.php',
//      'hits' => '18',
//      'memory_consumption' => '9528',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/AbstractNormalizer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/AbstractNormalizer.php',
//      'hits' => '18',
//      'memory_consumption' => '22000',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/FragmentRendererInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/FragmentRendererInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '2992',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/LateDataCollectorInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/LateDataCollectorInterface.php',
//      'hits' => '38',
//      'memory_consumption' => '1992',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/And.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/And.php',
//      'hits' => '0',
//      'memory_consumption' => '2016',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/stopwatch/Stopwatch.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/stopwatch/Stopwatch.php',
//      'hits' => '39',
//      'memory_consumption' => '12112',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467178916'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Filter.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Filter.php',
//      'hits' => '1',
//      'memory_consumption' => '5640',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/GetResponseForControllerResultEvent.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/GetResponseForControllerResultEvent.php',
//      'hits' => '8',
//      'memory_consumption' => '4016',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/Controller/ProfilerController.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/Controller/ProfilerController.php',
//      'hits' => '26',
//      'memory_consumption' => '36968',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472782372'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Profiler/Profile.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Profiler/Profile.php',
//      'hits' => '21',
//      'memory_consumption' => '16968',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Api/BootableProviderInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Api/BootableProviderInterface.php',
//      'hits' => '41',
//      'memory_consumption' => '2272',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Matcher/UrlMatcherInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Matcher/UrlMatcherInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '2616',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/psr/log/Psr/Log/AbstractLogger.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/psr/log/Psr/Log/AbstractLogger.php',
//      'hits' => '41',
//      'memory_consumption' => '9648',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1356090051'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/ExceptionHandler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/ExceptionHandler.php',
//      'hits' => '41',
//      'memory_consumption' => '31176',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/Controller/RouterController.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/Controller/RouterController.php',
//      'hits' => '15',
//      'memory_consumption' => '8904',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1472782372'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeInterface.php',
//      'hits' => '2',
//      'memory_consumption' => '2816',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/RouteCollection.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/RouteCollection.php',
//      'hits' => '48',
//      'memory_consumption' => '18952',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/AssetServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/AssetServiceProvider.php',
//      'hits' => '50',
//      'memory_consumption' => '10056',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/RouterListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/RouterListener.php',
//      'hits' => '48',
//      'memory_consumption' => '12120',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/ControllerMetadata/ArgumentMetadata.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/ControllerMetadata/ArgumentMetadata.php',
//      'hits' => '28',
//      'memory_consumption' => '6224',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/ResponseListener.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/EventListener/ResponseListener.php',
//      'hits' => '31',
//      'memory_consumption' => '4088',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/Exception/InvalidArgumentException.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/Exception/InvalidArgumentException.php',
//      'hits' => '2',
//      'memory_consumption' => '1776',
//      'last_used' => 'Sat Sep 17 22:13:56 2016',
//      'last_used_timestamp' => '1474168436',
//      'timestamp' => '1471266928'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Embed.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Embed.php',
//      'hits' => '2',
//      'memory_consumption' => '6720',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/FilterControllerArgumentsEvent.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/FilterControllerArgumentsEvent.php',
//      'hits' => '28',
//      'memory_consumption' => '3936',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/HandlerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Handler/HandlerInterface.php',
//      'hits' => '16',
//      'memory_consumption' => '5920',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/Exception/FatalThrowableError.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/Exception/FatalThrowableError.php',
//      'hits' => '2',
//      'memory_consumption' => '3944',
//      'last_used' => 'Sat Sep 17 21:21:16 2016',
//      'last_used_timestamp' => '1474165276',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/ff/ff1c827b8c327d8814bbe0ea511764c382ed67e69bfcf665f2fed128acdd4fdc.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/ff/ff1c827b8c327d8814bbe0ea511764c382ed67e69bfcf665f2fed128acdd4fdc.php',
//      'hits' => '10',
//      'memory_consumption' => '11480',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168151'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/ChainEncoder.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Encoder/ChainEncoder.php',
//      'hits' => '8',
//      'memory_consumption' => '7192',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/fd/fd70d6e7454af125a8564e6d1464a7caa92fc170439431f3496d7e3e757ed189.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/fd/fd70d6e7454af125a8564e6d1464a7caa92fc170439431f3496d7e3e757ed189.php',
//      'hits' => '9',
//      'memory_consumption' => '9456',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/src/controllers.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/src/controllers.php',
//      'hits' => '4',
//      'memory_consumption' => '6968',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1474168558'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/doctrine/collections/lib/Doctrine/Common/Collections/ArrayCollection.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/doctrine/collections/lib/Doctrine/Common/Collections/ArrayCollection.php',
//      'hits' => '2',
//      'memory_consumption' => '26528',
//      'last_used' => 'Sat Sep 17 22:13:56 2016',
//      'last_used_timestamp' => '1474168436',
//      'timestamp' => '1429050118'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Call.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Call.php',
//      'hits' => '1',
//      'memory_consumption' => '26912',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/pimple/pimple/src/Pimple/ServiceProviderInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/pimple/pimple/src/Pimple/ServiceProviderInterface.php',
//      'hits' => '50',
//      'memory_consumption' => '2256',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1441984235'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/SerializerAwareTrait.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/SerializerAwareTrait.php',
//      'hits' => '18',
//      'memory_consumption' => '2288',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/FinishRequestEvent.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/FinishRequestEvent.php',
//      'hits' => '28',
//      'memory_consumption' => '1664',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/91/91e078c90af597a0804e0282af6ab04c9d07307a6793581174ed923cbd5026cd.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/91/91e078c90af597a0804e0282af6ab04c9d07307a6793581174ed923cbd5026cd.php',
//      'hits' => '9',
//      'memory_consumption' => '97848',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/HIncludeFragmentRenderer.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Fragment/HIncludeFragmentRenderer.php',
//      'hits' => '46',
//      'memory_consumption' => '13024',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/If.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/If.php',
//      'hits' => '2',
//      'memory_consumption' => '8040',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Matcher/RequestMatcherInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Matcher/RequestMatcherInterface.php',
//      'hits' => '48',
//      'memory_consumption' => '2560',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/psr/log/Psr/Log/LoggerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/psr/log/Psr/Log/LoggerInterface.php',
//      'hits' => '41',
//      'memory_consumption' => '8488',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1356090051'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Parser.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Parser.php',
//      'hits' => '2',
//      'memory_consumption' => '40352',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/Package.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/Package.php',
//      'hits' => '46',
//      'memory_consumption' => '5712',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467388800'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/Controller/ExceptionController.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/web-profiler-bundle/Controller/ExceptionController.php',
//      'hits' => '15',
//      'memory_consumption' => '11072',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1472782372'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Profiler/FileProfilerStorage.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Profiler/FileProfilerStorage.php',
//      'hits' => '38',
//      'memory_consumption' => '26936',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Formatter/NormalizerFormatter.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/monolog/monolog/src/Monolog/Formatter/NormalizerFormatter.php',
//      'hits' => '16',
//      'memory_consumption' => '25448',
//      'last_used' => 'Sat Sep 17 21:59:39 2016',
//      'last_used_timestamp' => '1474167579',
//      'timestamp' => '1469762632'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Token.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Token.php',
//      'hits' => '2',
//      'memory_consumption' => '12840',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser.php',
//      'hits' => '2',
//      'memory_consumption' => '2400',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/58/58caf5374c43c26220fd2522128b9fe4c9182296bdd94cf7c037e100b8753dd1.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/58/58caf5374c43c26220fd2522128b9fe4c9182296bdd94cf7c037e100b8753dd1.php',
//      'hits' => '9',
//      'memory_consumption' => '17632',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Markup.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Markup.php',
//      'hits' => '10',
//      'memory_consumption' => '3312',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/PackageInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/asset/PackageInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '2600',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1467388800'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Cloner/ClonerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/var-dumper/Cloner/ClonerInterface.php',
//      'hits' => '39',
//      'memory_consumption' => '2064',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472634342'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Concat.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Concat.php',
//      'hits' => '0',
//      'memory_consumption' => '2016',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Profiler.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Extension/Profiler.php',
//      'hits' => '39',
//      'memory_consumption' => '4712',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/bf/bff21f5ce79ae2c6ea4926bc7b2a6f098e6ec6387ac5fd2cdbecec8c1355710c.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/var/cache/twig/bf/bff21f5ce79ae2c6ea4926bc7b2a6f098e6ec6387ac5fd2cdbecec8c1355710c.php',
//      'hits' => '9',
//      'memory_consumption' => '101944',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1474168278'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeTraverser.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeTraverser.php',
//      'hits' => '2',
//      'memory_consumption' => '7136',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolver/RequestValueResolver.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolver/RequestValueResolver.php',
//      'hits' => '46',
//      'memory_consumption' => '3224',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/BufferingLogger.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/debug/BufferingLogger.php',
//      'hits' => '41',
//      'memory_consumption' => '3040',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471959555'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ControllerResolverInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ControllerResolverInterface.php',
//      'hits' => '46',
//      'memory_consumption' => '3832',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/CallbackResolver.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/CallbackResolver.php',
//      'hits' => '48',
//      'memory_consumption' => '6296',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/RoutingServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/RoutingServiceProvider.php',
//      'hits' => '50',
//      'memory_consumption' => '10768',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/pimple/pimple/src/Pimple/Container.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/pimple/pimple/src/Pimple/Container.php',
//      'hits' => '50',
//      'memory_consumption' => '20488',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1441984235'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyAccessorBuilder.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyAccessorBuilder.php',
//      'hits' => '18',
//      'memory_consumption' => '5912',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471266928'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Conditional.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Conditional.php',
//      'hits' => '0',
//      'memory_consumption' => '4152',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/If.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/If.php',
//      'hits' => '0',
//      'memory_consumption' => '6192',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Constant.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Constant.php',
//      'hits' => '2',
//      'memory_consumption' => '2936',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Debug/TraceableEventDispatcher.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Debug/TraceableEventDispatcher.php',
//      'hits' => '39',
//      'memory_consumption' => '6152',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/PostResponseEvent.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Event/PostResponseEvent.php',
//      'hits' => '28',
//      'memory_consumption' => '3240',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeVisitorInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/NodeVisitorInterface.php',
//      'hits' => '2',
//      'memory_consumption' => '3504',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Do.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Do.php',
//      'hits' => '2',
//      'memory_consumption' => '3240',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/GetAttr.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/GetAttr.php',
//      'hits' => '0',
//      'memory_consumption' => '7648',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Template.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Template.php',
//      'hits' => '28',
//      'memory_consumption' => '47288',
//      'last_used' => 'Sat Sep 17 22:17:12 2016',
//      'last_used_timestamp' => '1474168632',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/paragonie/random_compat/lib/random.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/paragonie/random_compat/lib/random.php',
//      'hits' => '54',
//      'memory_consumption' => '2200',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1459663207'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/ConfigDataCollector.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/DataCollector/ConfigDataCollector.php',
//      'hits' => '38',
//      'memory_consumption' => '21064',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/NormalizerInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/serializer/Normalizer/NormalizerInterface.php',
//      'hits' => '18',
//      'memory_consumption' => '3240',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471619572'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/SimpleTest.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/SimpleTest.php',
//      'hits' => '2',
//      'memory_consumption' => '5496',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/src/app.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/src/app.php',
//      'hits' => '22',
//      'memory_consumption' => '2696',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1474168091'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/BaseNodeVisitor.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/BaseNodeVisitor.php',
//      'hits' => '2',
//      'memory_consumption' => '4856',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Matcher/RedirectableUrlMatcher.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/routing/Matcher/RedirectableUrlMatcher.php',
//      'hits' => '46',
//      'memory_consumption' => '6288',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471359504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Use.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Use.php',
//      'hits' => '2',
//      'memory_consumption' => '5840',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Extends.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Extends.php',
//      'hits' => '2',
//      'memory_consumption' => '4272',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/HttpFoundationExtension.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/twig-bridge/Extension/HttpFoundationExtension.php',
//      'hits' => '46',
//      'memory_consumption' => '10608',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472630853'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolver.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/http-kernel/Controller/ArgumentResolver.php',
//      'hits' => '46',
//      'memory_consumption' => '7072',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1472916504'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Include.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/TokenParser/Include.php',
//      'hits' => '2',
//      'memory_consumption' => '5632',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/VarDumperServiceProvider.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/silex/silex/src/Silex/Provider/VarDumperServiceProvider.php',
//      'hits' => '41',
//      'memory_consumption' => '6040',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471888221'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyAccessorInterface.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/symfony/property-access/PropertyAccessorInterface.php',
//      'hits' => '18',
//      'memory_consumption' => '7352',
//      'last_used' => 'Sat Sep 17 22:17:13 2016',
//      'last_used_timestamp' => '1474168633',
//      'timestamp' => '1471266928'
//    ),
//    '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Div.php' => array(
//      'full_path' => '/Users/bjd/workspace/caxy/caf/discoverdesign/docroot/opcache-status/vendor/twig/twig/lib/Twig/Node/Expression/Binary/Div.php',
//      'hits' => '0',
//      'memory_consumption' => '2016',
//      'last_used' => 'Sat Sep 17 22:11:18 2016',
//      'last_used_timestamp' => '1474168278',
//      'timestamp' => '1472752253'
//    )
//  )
//), 'OpCache\Model\Status', 'json');
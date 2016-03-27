<?php

include "../vendor/autoload.php";

$app = new \Silex\Application();
$config = \Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__ . '/../app/config.yml'));
$app['debug'] = true;
$app->register(new \SilexChatProvider\Provider\GitChatServiceProvider($config));
$app->mount('/api/', new \SilexChatProvider\Controller\ApiController());
$app->mount('/', new \SilexChatProvider\Controller\PageController());

$app->run();
<?php

include "../vendor/autoload.php";

$app = new \Silex\Application();
$config = \Symfony\Component\Yaml\Yaml::parse('../app/config.yml');
$app['debug'] = true;
$app->mount('/', new \GitChat\Controllers\PageController());

$app->run();
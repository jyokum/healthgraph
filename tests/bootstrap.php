<?php

error_reporting(E_ALL | E_STRICT);

require_once 'PHPUnit/TextUI/TestRunner.php';
require dirname(__DIR__) . '/vendor/autoload.php';

if (isset($GLOBALS['monolog_on']) && $GLOBALS['monolog_on']) {
    $log = new \Monolog\Logger('Log');
    $log->pushHandler(new \Monolog\Handler\StreamHandler($GLOBALS['monolog_log']));
}

// Register services with the GuzzleTestCase
Guzzle\Tests\GuzzleTestCase::setMockBasePath(__DIR__ . DIRECTORY_SEPARATOR . 'mock');

Guzzle\Tests\GuzzleTestCase::setServiceBuilder(Guzzle\Service\Builder\ServiceBuilder::factory(array(
        'client' => array(
            'class' => 'HealthGraph\HealthGraphClient',
            'params' => array(
                'logger' => $log
            )
        )
)));

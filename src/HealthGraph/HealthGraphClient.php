<?php

namespace HealthGraph;

use HealthGraph\Common\Iterator\HealthGraphIteratorFactory;
use Guzzle\Service\Client;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;

class HealthGraphClient extends Client
{

    /**
     * Factory method to create a new HealthGraphClient
     *
     * @param array|Collection $config Configuration data. Array keys:
     *    base_url - Base URL of web service
     *
     * @return HealthGraphClient
     *
     * @TODO update factory method and docblock for parameters
     */
    public static function factory($config = array(), $logger = null)
    {
        $default = array('base_url' => 'https://api.runkeeper.com');

        $required = array(
            'base_url',
            'access_token',
            'token_type',
        );
        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'));
        $client->setConfig($config);
        $client->setDefaultOption(
            'headers/Authorization',
            $config->get('token_type') . ' ' . $config->get('access_token')
        );
        $client->setDescription(ServiceDescription::factory(__DIR__ . DIRECTORY_SEPARATOR . 'client.json'));

        // Set the iterator resource factory based on the provided iterators config
        $clientClass = get_class();
        $prefix = substr($clientClass, 0, strrpos($clientClass, '\\'));
        $client->setResourceIteratorFactory(
            new HealthGraphIteratorFactory(array("{$prefix}\\Common\\Iterator"))
        );

        if ($logger) {
            $adapter = new \Guzzle\Log\PsrLogAdapter($logger);
            $logPlugin = new \Guzzle\Plugin\Log\LogPlugin(
                $adapter,
                \Guzzle\Log\MessageFormatter::DEBUG_FORMAT
            );
            $client->addSubscriber($logPlugin);
        }

        $client->getUser();
        return $client;
    }
}

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
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => 'https://api.runkeeper.com',
            'logger' => FALSE,
        );
        $required = array('base_url');

        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'));
        $client->setConfig($config);

        $client->setDescription(ServiceDescription::factory(__DIR__ . DIRECTORY_SEPARATOR . 'client.json'));

        // Set the iterator resource factory based on the provided iterators config
        $clientClass = get_class();
        $prefix = substr($clientClass, 0, strrpos($clientClass, '\\'));
        $client->setResourceIteratorFactory(
            new HealthGraphIteratorFactory(array("{$prefix}\\Common\\Iterator"))
        );

        if ($config->get('logger')) {
            $adapter = new \Guzzle\Log\PsrLogAdapter($config->get('logger'));
            $logPlugin = new \Guzzle\Plugin\Log\LogPlugin(
                $adapter,
                \Guzzle\Log\MessageFormatter::DEBUG_FORMAT
            );
            $client->addSubscriber($logPlugin);
        }

        return $client;
    }
}

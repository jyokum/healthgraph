<?php

namespace HealthGraph;

use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;

class Client extends \Guzzle\Service\Client {

  public static function factory($config = array()) {
    // Provide a hash of default client configuration options
    $default = array('base_url' => 'https://api.runkeeper.com');

    // The following values are required when creating the client
    $required = array(
      'base_url',
      'access_token',
      'token_type',
    );

    // Merge in default settings and validate the config
    $config = Collection::fromConfig($config, $default, $required);
    // Create a new HealthGraph client
    $client = new self($config->get('base_url'), $config);
    $client->setDefaultOption('headers/Authorization', $config->get('token_type') . ' ' . $config->get('access_token'));
    $client->setDescription(ServiceDescription::factory('healthgraph.json'));
    
    $client->getUser();

    return $client;
  }

}
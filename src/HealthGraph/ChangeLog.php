<?php

namespace HealthGraph;

class ChangeLog {

  const TYPE = 'application/vnd.com.runkeeper.ChangeLog+json';

  public function __construct(&$client, $uri) {
    $this->client = & $client;
    $response = $this->client->request($uri, self::TYPE);
    if ($response['success']) {
      foreach ($response['data'] as $key => $value) {
        $this->$key = $value;
      }
    }
  }

}

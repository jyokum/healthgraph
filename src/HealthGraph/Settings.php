<?php

namespace HealthGraph;

class Settings {

  const TYPE = 'application/vnd.com.runkeeper.Settings+json';

  private $uri;
  private $client;

  public function __construct(&$client, $uri) {
    $this->client = & $client;
    $response = $this->client->request($uri, self::TYPE);
    foreach ($response['data'] as $key => $value) {
      $this->$key = $value;
    }
  }

  public function update($data) {
    $response = $this->client->request($this->uri, self::TYPE, $data, 'PUT');
    foreach ($response['data'] as $key => $value) {
      $this->$key = $value;
    }
  }

}

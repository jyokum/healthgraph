<?php

namespace HealthGraph;

class Records {

  const TYPE = 'application/vnd.com.runkeeper.Records+json';

  private $items;

  public function __construct(&$client, $uri) {
    $this->client = & $client;
    $response = $this->client->request($uri, self::TYPE);
    if ($response['success']) {
      foreach ($response['data'] as $value) {
        foreach ($value->stats as $stat) {
          $this->items[$value->activity_type][$stat->stat_type] = $stat->value;
        }
      }
    }
  }

  public function items() {
    return $this->items;
  }

}

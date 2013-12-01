<?php

namespace HealthGraph;

abstract class Feed {

  protected $client;
  protected $uri;

  public function __construct(&$client, $uri) {
    $this->client = & $client;
    $this->uri = $uri;
  }

  protected function getItem($uri, $type) {
    $result = $this->client->request($uri, $type);
    return ($result['success']) ? $result['data'] : FALSE;
  }

  public function items() {
    $response = $this->client->request($this->uri, static::TYPE);
    $this->size = (isset($response['data']->size)) ? $response['data']->size : 0;
    $this->items = (isset($response['data']->items)) ? $response['data']->items : array();
    $this->next = (isset($response['data']->next)) ? $response['data']->next : '';
    $this->previous = (isset($response['data']->previous)) ? $response['data']->previous : '';
    return $this->items;
  }

  abstract function detail($uri);

  public function next() {
    return $this->next;
  }

  public function previous() {
    return $this->previous;
  }

  public function size() {
    return $this->size;
  }

}

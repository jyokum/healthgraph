<?php

namespace HealthGraph;

class BackgroundActivitySetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.BackgroundActivitySetFeed+json';

  public function __construct(&$client, $uri) {
    parent::__construct($client, $uri);
  }

  public function items() {
    $this->getItems($this->uri, self::TYPE);
    foreach ($this->items as &$item) {
      $item->timestamp = strtotime($item->timestamp);
      $item->calories_burned = (isset($item->calories_burned)) ? $item->calories_burned : NULL;
      $item->steps = (isset($item->steps)) ? $item->steps : NULL;
    }
    return $this->items;
  }

  public function detail($uri) {
    $type = 'application/vnd.com.runkeeper.BackgroundActivitySet+json';
    return $this->getItem($uri, $type);
  }

}

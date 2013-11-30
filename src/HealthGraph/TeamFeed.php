<?php

namespace HealthGraph;

class TeamFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.TeamFeed+json';

  public function __construct(&$client, $uri) {
    parent::__construct($client, $uri);
  }

  public function items() {
    $this->getItems($this->uri, self::TYPE);
    return $this->items;
  }

  public function detail($uri) {
    $type = 'application/vnd.com.runkeeper.Member+json';
    return $this->getItem($uri, $type);
  }

}

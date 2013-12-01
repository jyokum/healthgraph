<?php

namespace HealthGraph;

class TeamFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.TeamFeed+json';

  public function detail($uri) {
    $type = 'application/vnd.com.runkeeper.Member+json';
    return $this->getItem($uri, $type);
  }

}

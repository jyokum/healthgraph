<?php

namespace HealthGraph;

class StrengthTrainingActivityFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.StrengthTrainingActivityFeed+json';

  public function detail($uri) {
    $type = 'application/vnd.com.runkeeper.StrengthTrainingActivity+json';
    return $this->getItem($uri, $type);
  }

}

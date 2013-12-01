<?php

namespace HealthGraph;

class GeneralMeasurementSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.GeneralMeasurementSetFeed+json';

  public function detail($uri) {
    $type = 'application/vnd.com.runkeeper.GeneralMeasurementSet+json';
    return $this->getItem($uri, $type);
  }

}

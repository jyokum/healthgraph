<?php

namespace HealthGraph;

class DiabetesMeasurementSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.DiabetesMeasurementSetFeed+json';

  public function detail($uri) {
    $type = 'application/vnd.com.runkeeper.DiabetesMeasurementSet+json';
    return $this->getItem($uri, $type);
  }

}

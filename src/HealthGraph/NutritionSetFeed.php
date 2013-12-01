<?php

namespace HealthGraph;

class NutritionSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.NutritionSetFeed+json';

  public function detail($uri) {
    $type = 'application/vnd.com.runkeeper.NutritionSet+json';
    return $this->getItem($uri, $type);
  }

}

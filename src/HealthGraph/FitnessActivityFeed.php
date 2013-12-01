<?php

namespace HealthGraph;

class FitnessActivityFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.FitnessActivityFeed+json';

  public function items() {
    parent::items();
    foreach ($this->items as &$item) {
      $item->start_time = strtotime($item->start_time);
      $item->total_calories = (isset($item->total_calories)) ? $item->total_calories : NULL;
    }
    return $this->items;
  }

  public function detail($uri) {
    $type = 'application/vnd.com.runkeeper.FitnessActivity+json';
    return $this->getItem($uri, $type);
  }

  public function summary($uri) {
    $type = 'application/vnd.com.runkeeper.FitnessActivitySummary+json';
    return $this->getItem($uri, $type);
  }

}

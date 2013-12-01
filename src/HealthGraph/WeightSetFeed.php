<?php

namespace HealthGraph;

class WeightSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.WeightSetFeed+json';

  public function items() {
    parent::items();
    foreach ($this->items as &$item) {
      $item->timestamp = strtotime($item->timestamp);
      $item->weight = (isset($item->weight)) ? $item->weight : NULL;
      $item->free_mass = (isset($item->free_mass)) ? $item->free_mass : NULL;
      $item->fat_percent = (isset($item->fat_percent)) ? $item->fat_percent : NULL;
      $item->mass_weight = (isset($item->mass_weight)) ? $item->mass_weight : NULL;
      $item->bmi = (isset($item->bmi)) ? $item->bmi : NULL;
    }
    return $this->items;
  }

  public function detail($uri) {
    $type = 'application/vnd.com.runkeeper.WeightSet+json';
    return $this->getItem($uri, $type);
  }

}

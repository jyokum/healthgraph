<?php

namespace HealthGraph;

class SleepSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.SleepSetFeed+json';

  public function items() {
    parent::items();
    foreach ($this->items as &$item) {
      $item->timestamp = strtotime($item->timestamp);
      $item->total_sleep = (isset($item->total_sleep)) ? $item->total_sleep : NULL;
      $item->deep = (isset($item->deep)) ? $item->deep : NULL;
      $item->rem = (isset($item->rem)) ? $item->rem : NULL;
      $item->light = (isset($item->light)) ? $item->light : NULL;
      $item->awake = (isset($item->awake)) ? $item->awake : NULL;
      $item->times_woken = (isset($item->times_woken)) ? $item->times_woken : NULL;
    }
    return $this->items;
  }

  public function detail($uri) {
    $type = 'application/vnd.com.runkeeper.SleepSet+json';
    return $this->getItem($uri, $type);
  }

}

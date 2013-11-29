<?php

session_start();
require_once 'lib/healthgraph.php';

if (!isset($_SESSION['token'])) {
  header("Location: authorization.php");
  die();
}
else {
  echo "<a href='authorization.php?revoke'>Disconnect</a>";

  $hgu = new \HealthGraph\User($_SESSION['token']->access_token);
//  $hgu->profile();
//  $hgu->settings();
//  $hgu->fitness_activities();
//  $hgu->strength_training_activities();
//  $hgu->background_activities();
//  $hgu->sleep();
//  $hgu->nutrition();
//  $hgu->weight();
//  $hgu->general_measurements();
//  $hgu->diabetes();
//  $hgu->records();
//  $hgu->team();
//  $hgu->change_log();
  $items = $hgu->fitness_activities()->items();
  var_dump($items);
  $detail = $hgu->fitness_activities()->detail($items[0]->uri);
  var_dump($detail);
//  $summary = $hgu->fitness_activities()->summary($items[0]->uri);
//  var_dump($summary);

  var_dump($hgu);
//  $hgu->profile()->setAthleteType('Athlete');
}
?>

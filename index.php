<?php

session_start();
$loader = require 'vendor/autoload.php';;

if (!isset($_SESSION['token'])) {
  header("Location: authorization.php");
  die();
}
else {
  echo "<a href='authorization.php?revoke'>Disconnect</a>";
  $hgu = new \HealthGraph\User($_SESSION['token']->access_token);
  $hgu->profile();
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
  $hgu->profile()->setAthleteType('Athlete');
//  $items = $hgu->records()->items();
//  var_dump($items);
  var_dump($hgu);
}

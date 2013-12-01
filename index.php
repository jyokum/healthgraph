<?php

$loader = require 'vendor/autoload.php';

require 'settings.php';
/**
 * settings.php contains info that should not be committed to the repo
 * 
 * $client_id = 'your_client_id';
 * $client_secret = 'your_client_secret';
 */

/**
 * Token data could be pulled from a database or session or hardcoded. For
 * simplicity we are using a file. Make sure your server can read/write this file.
 */
$token_file = './token.json';
if (file_exists($token_file)) {
  $token = json_decode(file_get_contents($token_file));
}

if (!isset($token)) {
  header("Location: authorization.php");
  die();
}
else {
  echo "<a href='authorization.php?revoke'>Disconnect</a>";
  $hgu = new \HealthGraph\User($token->access_token);
  $hgu->profile();
  $hgu->settings();
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
//  $hgu->profile()->setAthleteType('Athlete');
  $items = $hgu->weight()->items();
  var_dump($items);
  var_dump($hgu);
}

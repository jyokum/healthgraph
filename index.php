<?php

session_start();
require_once 'settings.php'; // contains info we don't want committed to repo
// $client_id = 'XXXXXXXXXXXXXXXX';
// $client_secret = 'XXXXXXXXXXXXXXXX';

require_once 'lib/healthgraph.php';
$hgc = new \HealthGraph\Client();

$redirect_url = 'http://localhost/healthgraph/index.php';
if (isset($_REQUEST['code'])) {
  $_SESSION['token'] = $hgc->authorize($_REQUEST['code'], $client_id, $client_secret, $redirect_url);
  header("Location: $redirect_url");
  die();
}
elseif (isset($_REQUEST['disconnect'])) {
  $hgc->deauthorize($_SESSION['token']->access_token);
  unset($_SESSION['token']);
  header("Location: $redirect_url");
  die();
}

if (isset($_SESSION['token'])) {
  echo "<a href='?disconnect'>Disconnect</a>";

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
//  var_dump($hgu->sleep()->items());
//  var_dump($hgu->fitness_activities()->items());

//  var_dump($hgu);
//  $hgu->profile()->setAthleteType('Athlete');
  var_dump($hgu);
}
else {
  $connect = $hgc->getAuthorizationLink($client_id, $redirect_url);
  echo "<a href='$connect'>Connect</a>";
}
?>

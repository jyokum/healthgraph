<?php

require_once 'lib/healthgraph.php';
require_once 'settings.php'; // contains info we don't want committed to git repo
// $access_token = 'XXXXXXXXXXXXXXXX';

$hgu = new \HealthGraph\User($access_token);
$hgu->profile();
//$hgu->settings();
//$hgu->fitness_activities();
//$hgu->strength_training_activities();
//$hgu->background_activities();
//$hgu->sleep();
//$hgu->nutrition();
//$hgu->weight();
//$hgu->general_measurements();
//$hgu->diabetes();
//$hgu->records();
//$hgu->team();
//$hgu->change_log();
var_dump($hgu->sleep()->items());
var_dump($hgu->fitness_activities()->items());

var_dump($hgu);

?>

<?php

session_start();
$loader = require 'vendor/autoload.php';
require 'settings.php'; // contains info we don't want committed to repo

$auth_url = 'http://localhost/healthgraph/authorization.php';
$home = 'http://localhost/healthgraph/';

if (isset($_REQUEST['code'])) {
  $_SESSION['token'] = \HealthGraph\Authorization::authorize($_REQUEST['code'], $client_id, $client_secret, $auth_url);

  header("Location: $home");
  die();
}
elseif (isset($_REQUEST['revoke'])) {
  \HealthGraph\Authorization::deauthorize($_SESSION['token']->access_token);
  unset($_SESSION['token']);
  
  header("Location: $auth_url");
  die();
}
else {
  if (isset($_REQUEST['error'])) {
    echo "<h2>You seem to have refused access</h2>";
  }
  echo \HealthGraph\Authorization::getAuthorizationButton($client_id, $auth_url);
}
?>

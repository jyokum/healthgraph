<?php

$loader = require 'vendor/autoload.php';
require 'settings.php'; // contains info we don't want committed to repo

$auth_url = 'http://localhost/healthgraph/authorization.php';
$home = 'http://localhost/healthgraph/';
$token_file = './token.json';

if (isset($_REQUEST['code'])) {
  $token = \HealthGraph\Authorization::authorize($_REQUEST['code'], $client_id, $client_secret, $auth_url);
  if ($token) {
    file_put_contents($token_file, json_encode($token));
  }

  header("Location: $home");
  die();
}
elseif (isset($_REQUEST['revoke'])) {
  \HealthGraph\Authorization::deauthorize($token->access_token);
  unlink($token_file);

  header("Location: $auth_url");
  die();
}
else {
  if (isset($_REQUEST['error'])) {
    echo "<h2>You seem to have refused access</h2>";
  }
  $button = \HealthGraph\Authorization::getAuthorizationButton($client_id, $auth_url);
  echo $button['html'];
}

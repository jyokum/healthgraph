<?php

namespace HealthGraph;

class Authorization {

  public static function getAuthorizationButton($client_id, $redirect_url, $text = 'connect', $color = 'blue', $caption = 'white', $size = 200, $url = 'https://runkeeper.com/apps/authorize') {
    $data = array(
      'client_id' => $client_id,
      'response_type' => 'code',
      'redirect_uri' => $redirect_url,
    );
    $link = $url . '?' . http_build_query($data);
    $text = (in_array($text, array('connect', 'login'))) ? $text : 'connect';
    $color = (in_array($color, array('blue', 'grey', 'black'))) ? $color : 'blue';
    $caption = (in_array($caption, array('white', 'black'))) ? $caption : 'white';
    switch ($size) {
      case '300':
        $size = '300x57';
        break;

      case '600':
        $size = '600x114';
        break;

      default:
        $size = '200x38';
        break;
    }
    $image = "http://static1.runkeeper.com/images/assets/$text-$color-$caption-$size.png";
    $link = "<a href='$link'><img src='$image' /></a>";
    return $link;
  }

  public static function getAuthorizationLink($client_id, $redirect_url, $url = 'https://runkeeper.com/apps/authorize') {
    $data = array(
      'client_id' => $client_id,
      'response_type' => 'code',
      'redirect_uri' => $redirect_url,
    );
    return $url . '?' . http_build_query($data);
  }

  public static function authorize($authorization_code, $client_id, $client_secret, $redirect_url, $url = 'https://runkeeper.com/apps/token') {
    $params = array(
      'grant_type' => 'authorization_code',
      'code' => $authorization_code,
      'client_id' => $client_id,
      'client_secret' => $client_secret,
      'redirect_uri' => $redirect_url,
    );
    $client = new \HealthGraph\Client();
    $result = $client->request($url, NULL, $params, 'POST');
    return ($result['success']) ? $result['data'] : FALSE;
  }

  public static function deauthorize($access_token, $url = 'https://runkeeper.com/apps/de-authorize') {
    $params = array(
      'access_token' => $access_token,
    );
    $client = new \HealthGraph\Client();
    $result = $client->request($url, NULL, $params, 'POST');
    return ($result['success']) ? $result['data'] : FALSE;
  }

}

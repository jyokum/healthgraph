<?php

namespace HealthGraph;

class Authorization {

  /**
   * Generates a button for establishing a connection with a RunKeeper account.
   *
   * @param string $client_id
   *   The unique identifier that your application received upon registration.
   * @param string $redirect_url
   *   The page on your site where the user will be redirected after accepting
   *   or denying the access request.
   * @param string $text
   *   Type of button to generate. Default is "connect", valid options are: connect, login
   * @param string $color
   *   Color to use for button text. Default is "blue", valid options are: blue, grey, black
   * @param string $caption
   *   Color to use for button caption. Default is 200, valid options are: white, black
   * @param int $size
   *   Width of the button. Valid options are: 200, 300, 600
   * @param string $url
   * @return array
   */
  public static function getAuthorizationButton($client_id, $redirect_url, $state = '', $text = 'connect', $color = 'blue', $caption = 'white', $size = 200, $url = 'https://runkeeper.com/apps/authorize') {
    $link = self::getAuthorizationLink($client_id, $redirect_url, $state);
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
    $button = array(
      'link' => $link,
      'image' => $image,
      'html' => "<a href='$link'><img src='$image' /></a>",
    );
    return $button;
  }

  /**
   * Generates a link for establishing a connection with a RunKeeper account.
   *
   * @param string $client_id
   *   The unique identifier that your application received upon registration.
   * @param string $redirect_url
   *   The page on your site where the user will be redirected after accepting
   *   or denying the access request.
   * @param string $url
   * @return string
   */
  public static function getAuthorizationLink($client_id, $redirect_url, $state = '', $url = 'https://runkeeper.com/apps/authorize') {
    $data = array(
      'client_id' => $client_id,
      'response_type' => 'code',
      'redirect_uri' => $redirect_url,
      'state' => $state,
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

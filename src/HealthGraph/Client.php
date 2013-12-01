<?php

namespace HealthGraph;

class Client {

  protected $api_base_url;
  private $token;

  public function __construct($api_base_url = 'https://api.runkeeper.com') {
    $this->api_base_url = $api_base_url;
  }

  public function getToken() {
    return $this->token;
  }

  public function setToken($access_token, $token_type = 'Bearer') {
    $this->token = new \stdClass();
    $this->token->access_token = $access_token;
    $this->token->token_type = $token_type;
    return $this;
  }

  public function request($uri, $accept = 'application/*', $data = array(), $type = 'GET') {
    // is this an absolute URL or just a segment
    if (filter_var($uri, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) {
      $url = $uri;
    }
    else {
      $url = $this->api_base_url . $uri;
    }
    $ch = curl_init($url);

    $options[CURLOPT_SSL_VERIFYPEER] = FALSE;
    $options[CURLINFO_HEADER_OUT] = TRUE;
    $options[CURLOPT_RETURNTRANSFER] = TRUE;

    switch ($type) {
      case 'GET':
        $options[CURLOPT_HTTPHEADER][] = 'Authorization: ' . $this->token->token_type . ' ' . $this->token->access_token;
        $options[CURLOPT_HTTPHEADER][] = 'Accept: ' . $accept;

        break;

      case 'POST':
        $options[CURLOPT_POST] = TRUE;
        $options[CURLOPT_POSTFIELDS] = $data;

        break;

      case 'PUT':
        $options[CURLOPT_HTTPHEADER][] = 'Authorization: ' . $this->token->token_type . ' ' . $this->token->access_token;
        $options[CURLOPT_HTTPHEADER][] = 'Content-Type: ' . $accept;
        $options[CURLOPT_CUSTOMREQUEST] = 'PUT';
        $options[CURLOPT_POSTFIELDS] = json_encode($data);
        break;

      default:
        break;
    }

    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    $info['data'] = $response;
    // @todo better error checking on response
    if (curl_errno($ch)) {
      $info['success'] = FALSE;
    }
    else {
      switch ($info['http_code']) {
        case '200':
          $json = json_decode($response);
          if (is_null($json) && strlen($response)) {
            // There is likely an HTML formatted error in the response
            $info['success'] = FALSE;
          }
          else {
            $info['success'] = TRUE;
            $info['data'] = $json;
          }
          break;

        default:
          $info['success'] = FALSE;
          break;
      }
    }
    curl_close($ch);

    return $info;
  }

}

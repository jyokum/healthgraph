<?php

namespace HealthGraph;

class Profile {

  const TYPE = 'application/vnd.com.runkeeper.Profile+json';

  private $uri;
  private $client;
  public $name = '';
  public $location = '';
  public $athlete_type = '';
  public $gender = '';
  public $birthday = '';
  public $elite = FALSE;
  public $profile = '';
  public $small_picture = '';
  public $normal_picture = '';
  public $medium_picture = '';
  public $large_picture = '';

  public function __construct(&$client, $uri) {
    $this->uri = $uri;
    $this->client = & $client;
    $response = $this->client->request($this->uri, self::TYPE);
    foreach ($response['data'] as $key => $value) {
      $this->$key = $value;
    }
  }

  public function update($values) {
    $response = $this->client->request($this->uri, self::TYPE, $values, 'PUT');
    if ($response['success']) {
      foreach ($response['data'] as $key => $value) {
        $this->$key = $value;
      }
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  public function setAthleteType($value) {
    $data = array('athlete_type' => $value);
    return $this->update($data);
  }

}

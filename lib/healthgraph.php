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

  public function getAuthorizationButton($client_id, $redirect_url, $text = 'connect', $color = 'blue', $caption = 'white', $size = 200, $url = 'https://runkeeper.com/apps/authorize') {
    $url = $this->getAuthorizationLink($client_id, $redirect_url, $url);
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
    $link = "<a href='$url'><img src='$image' /></a>";
    return $link;
  }

  public function getAuthorizationLink($client_id, $redirect_url, $url = 'https://runkeeper.com/apps/authorize') {
    $data = array(
      'client_id' => $client_id,
      'response_type' => 'code',
      'redirect_uri' => $redirect_url,
    );
    return $url . '?' . http_build_query($data);
  }

  public function authorize($authorization_code, $client_id, $client_secret, $redirect_url, $url = 'https://runkeeper.com/apps/token') {
    $params = array(
      'grant_type' => 'authorization_code',
      'code' => $authorization_code,
      'client_id' => $client_id,
      'client_secret' => $client_secret,
      'redirect_uri' => $redirect_url,
    );
    $result = $this->request($url, NULL, $params, 'POST');
    return ($result['success']) ? $result['data'] : FALSE;
  }

  public function deauthorize($access_token, $url = 'https://runkeeper.com/apps/de-authorize') {
    $params = array(
      'access_token' => $access_token,
    );
    $result = $this->request($url, NULL, $params, 'POST');
    return ($result['success']) ? $result['data'] : FALSE;
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
    $json = curl_exec($ch);
    $info = curl_getinfo($ch);
    $info['data'] = $json;
    // @todo better error checking on response
    if (curl_errno($ch)) {
      $info['success'] = FALSE;
      $return = FALSE;
    }
    else {
      $info['data'] = json_decode($json);
      switch ($info['http_code']) {
        case '200':
          $info['success'] = TRUE;
          break;

        default:
          $info['success'] = FALSE;
          $return = FALSE;
          break;
      }
    }
    curl_close($ch);

    return $info;
  }

}

abstract class Feed {

  private $uri;
  private $client;
  public $size;
  public $items;
  public $previous;
  public $next;

  protected function __construct(&$client, $uri, $type) {
    $this->client = & $client;
    $response = $this->client->request($uri, $type);
    $this->defaults($response['data']);
  }

  protected function defaults($data) {
    $this->size = (isset($data->size)) ? $data->size : 0;
    $this->items = (isset($data->items)) ? $data->items : array();
    $this->next = (isset($data->next)) ? $data->next : '';
    $this->previous = (isset($data->previous)) ? $data->previous : '';
  }

  public function items() {
    return $this->items;
  }

  public function next() {
    return $this->next;
  }

  public function previous() {
    return $this->previous;
  }

  public function size() {
    return $this->size;
  }

}

class User {

  const TYPE = 'application/vnd.com.runkeeper.User+json';

  private $client;
  private $userID;
  private $uri;

  public function __construct($access_token, $token_type = 'Bearer') {
    $this->uri = new \stdClass();
    $this->uri->user = '/user';
    $this->client = new \HealthGraph\Client();
    $this->client->setToken($access_token, $token_type);
    $this->get();
  }

  public function get() {
    $response = $this->client->request($this->uri->user, self::TYPE);
    if ($response['success']) {
      $data = $response['data'];
      $this->userID = $data->userID;
      $this->uri->profile = $data->profile;
      $this->uri->settings = $data->settings;
      $this->uri->fitness_activities = $data->fitness_activities;
      $this->uri->strength_training_activities = $data->strength_training_activities;
      $this->uri->background_activities = $data->background_activities;
      $this->uri->sleep = $data->sleep;
      $this->uri->nutrition = $data->nutrition;
      $this->uri->weight = $data->weight;
      $this->uri->general_measurements = $data->general_measurements;
      $this->uri->diabetes = $data->diabetes;
      $this->uri->records = $data->records;
      $this->uri->team = $data->team;
      $this->uri->change_log = $data->change_log;
    }
  }

  public function profile($refresh = FALSE) {
    if (!isset($this->profile) || $refresh) {
      $this->profile = new Profile($this->client, $this->uri->profile);
    }
    return $this->profile;
  }

  public function settings($refresh = FALSE) {
    if (!isset($this->settings) || $refresh) {
      $this->settings = new Settings($this->client, $this->uri->settings);
    }
    return $this->settings;
  }

  public function fitness_activities($refresh = FALSE) {
    if (!isset($this->fitness_activities) || $refresh) {
      $this->fitness_activities = new FitnessActivityFeed($this->client, $this->uri->fitness_activities);
    }
    return $this->fitness_activities;
  }

  public function strength_training_activities($refresh = FALSE) {
    if (!isset($this->strength_training_activities) || $refresh) {
      $this->strength_training_activities = new StrengthTrainingActivityFeed($this->client, $this->uri->strength_training_activities);
    }
    return $this->strength_training_activities;
  }

  public function background_activities($refresh = FALSE) {
    if (!isset($this->background_activities) || $refresh) {
      $this->background_activities = new BackgroundActivitySetFeed($this->client, $this->uri->background_activities);
    }
    return $this->background_activities;
  }

  public function sleep($refresh = FALSE) {
    if (!isset($this->sleep) || $refresh) {
      $this->sleep = new SleepSetFeed($this->client, $this->uri->sleep);
    }
    return $this->sleep;
  }

  public function nutrition($refresh = FALSE) {
    if (!isset($this->nutrition) || $refresh) {
      $this->nutrition = new NutritionSetFeed($this->client, $this->uri->nutrition);
    }
    return $this->nutrition;
  }

  public function weight($refresh = FALSE) {
    if (!isset($this->weight) || $refresh) {
      $this->weight = new WeightSetFeed($this->client, $this->uri->weight);
    }
    return $this->weight;
  }

  public function general_measurements($refresh = FALSE) {
    if (!isset($this->general_measurements) || $refresh) {
      $this->general_measurements = new GeneralMeasurementSetFeed($this->client, $this->uri->general_measurements);
    }
    return $this->general_measurements;
  }

  public function diabetes($refresh = FALSE) {
    if (!isset($this->diabetes) || $refresh) {
      $this->diabetes = new DiabetesMeasurementSetFeed($this->client, $this->uri->diabetes);
    }
    return $this->diabetes;
  }

  public function records() {
    if (!isset($this->records) || $refresh) {
      $this->records = new Records($this->client, $this->uri->records);
    }
    return $this->records;
  }

  public function team() {
    if (!isset($this->team) || $refresh) {
      $this->team = new TeamFeed($this->client, $this->uri->team);
    }
    return $this->team;
  }

  public function change_log() {
    if (!isset($this->change_log) || $refresh) {
      $this->change_log = new ChangeLog($this->client, $this->uri->change_log);
    }
    return $this->change_log;
  }

}

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
    foreach ($response['data'] as $key => $value) {
      $this->$key = $value;
    }
  }

  public function setAthleteType($value) {
    $data = array('athlete_type' => $value);
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

}

class Settings {

  const TYPE = 'application/vnd.com.runkeeper.Settings+json';

  private $uri;
  private $client;

  public function __construct(&$client, $uri) {
    $this->client = & $client;
    $response = $this->client->request($uri, self::TYPE);
    foreach ($response['data'] as $key => $value) {
      $this->$key = $value;
    }
  }

  public function update($data) {
    $response = $this->client->request($this->uri, self::TYPE, $data, 'PUT');
    foreach ($response['data'] as $key => $value) {
      $this->$key = $value;
    }
  }

}

class FitnessActivityFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.FitnessActivityFeed+json';

  public function __construct(&$client, $uri) {
    parent::__construct($client, $uri, self::TYPE);
    foreach ($this->items as &$item) {
      $item->start_time = strtotime($item->start_time);
      $item->total_calories = (isset($item->total_calories)) ? $item->total_calories : NULL;
    }
  }

}

class StrengthTrainingActivityFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.StrengthTrainingActivityFeed+json';

  public function __construct(&$client, $uri) {
    parent::__construct($client, $uri, self::TYPE);
  }

}

class BackgroundActivitySetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.BackgroundActivitySetFeed+json';

  public function __construct(&$client, $uri) {
    parent::__construct($client, $uri, self::TYPE);
    foreach ($this->items as &$item) {
      $item->timestamp = strtotime($item->timestamp);
      $item->calories_burned = (isset($item->calories_burned)) ? $item->calories_burned : NULL;
      $item->steps = (isset($item->steps)) ? $item->steps : NULL;
    }
  }

}

class SleepSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.SleepSetFeed+json';

  public function __construct(&$client, $uri) {
    parent::__construct($client, $uri, self::TYPE);
    foreach ($this->items as &$item) {
      $item->timestamp = strtotime($item->timestamp);
      $item->total_sleep = (isset($item->total_sleep)) ? $item->total_sleep : NULL;
      $item->deep = (isset($item->deep)) ? $item->deep : NULL;
      $item->rem = (isset($item->rem)) ? $item->rem : NULL;
      $item->light = (isset($item->light)) ? $item->light : NULL;
      $item->awake = (isset($item->awake)) ? $item->awake : NULL;
      $item->times_woken = (isset($item->times_woken)) ? $item->times_woken : NULL;
    }
  }

}

class NutritionSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.NutritionSetFeed+json';

  public function __construct(&$client, $uri) {
    parent::__construct($client, $uri, self::TYPE);
  }

}

class WeightSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.WeightSetFeed+json';

  public function __construct(&$client, $uri) {
    parent::__construct($client, $uri, self::TYPE);
    foreach ($this->items as &$item) {
      $item->timestamp = strtotime($item->timestamp);
      $item->weight = (isset($item->weight)) ? $item->weight : NULL;
      $item->free_mass = (isset($item->free_mass)) ? $item->free_mass : NULL;
      $item->fat_percent = (isset($item->fat_percent)) ? $item->fat_percent : NULL;
      $item->mass_weight = (isset($item->mass_weight)) ? $item->mass_weight : NULL;
      $item->bmi = (isset($item->bmi)) ? $item->bmi : NULL;
    }
  }

}

class GeneralMeasurementSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.GeneralMeasurementSetFeed+json';

  public function __construct(&$client, $uri) {
    parent::__construct($client, $uri, self::TYPE);
  }

}

class DiabetesMeasurementSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.DiabetesMeasurementSetFeed+json';

  public function __construct(&$client, $uri) {
    parent::__construct($client, $uri, self::TYPE);
  }

}

class Records {

  const TYPE = 'application/vnd.com.runkeeper.Records+json';

  private $items;

  public function __construct(&$client, $uri) {
    $this->client = & $client;
    $response = $this->client->request($uri, self::TYPE);
    if ($response['success']) {
      foreach ($response['data'] as $value) {
        foreach ($value->stats as $stat) {
          $this->items[$value->activity_type][$stat->stat_type] = $stat->value;
        }
      }
    }
  }

  public function items() {
    return $this->items;
  }

}

class TeamFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.TeamFeed+json';

  public function __construct(&$client, $uri) {
    parent::__construct($client, $uri, self::TYPE);
  }

}

class ChangeLog {

  const TYPE = 'application/vnd.com.runkeeper.ChangeLog+json';

  public function __construct(&$client, $uri) {
    $this->client = & $client;
    $response = $this->client->request($uri, self::TYPE);
    if ($response['success']) {
      foreach ($response['data'] as $key => $value) {
        $this->$key = $value;
      }
    }
  }

}
?>

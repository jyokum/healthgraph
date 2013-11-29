<?php

namespace HealthGraph;

class Base {

  protected $api_base_url = 'https://api.runkeeper.com';
  private $authorization_url = 'https://runkeeper.com/apps/authorize';
  private $token_url = 'https://runkeeper.com/apps/token';
  private $deauthorization_url = 'https://runkeeper.com/apps/de-authorize';
  private $token;

  public function __construct() {
    
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
    $ch = curl_init($this->api_base_url . $uri);

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
        $options[CURLOPT_PUT] = TRUE;
        $options[CURLOPT_POSTFIELDS] = json_encode($data);
        break;

      default:
        break;
    }

    curl_setopt_array($ch, $options);
    $json = curl_exec($ch);
    $info = curl_getinfo($ch);
    // @todo better error checking on response
    if (curl_errno($ch)) {
      $return = FALSE;
    }
    else {
      $return = json_decode($json);
    }
    curl_close($ch);

    return $return;
  }

}

abstract class Feed {

  public $size;
  public $items;
  public $previous;
  public $next;

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

class User extends \HealthGraph\Base {

  const TYPE = 'application/vnd.com.runkeeper.User+json';

  private $userID;
  private $uri;

  public function __construct($access_token, $token_type = 'Bearer') {
    parent::__construct();
    $this->uri = new \stdClass();
    $this->uri->user = '/user';
    $this->setToken($access_token, $token_type);
    $this->get();
  }

  public function get() {
    $data = $this->request($this->uri->user, self::TYPE);
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

  public function profile($refresh = FALSE) {
    if (!isset($this->profile) || $refresh) {
      $data = $this->request($this->uri->profile, Profile::TYPE);
      $this->profile = new Profile($data);
    }
    return $this->profile;
  }

  public function settings($refresh = FALSE) {
    if (!isset($this->settings) || $refresh) {
      $data = $this->request($this->uri->settings, Settings::TYPE);
      $this->settings = new Settings($data);
    }
    return $this->settings;
  }

  public function fitness_activities($refresh = FALSE) {
    if (!isset($this->fitness_activities) || $refresh) {
      $data = $this->request($this->uri->fitness_activities, FitnessActivityFeed::TYPE);
      $this->fitness_activities = new FitnessActivityFeed($data);
    }
    return $this->fitness_activities;
  }

  public function strength_training_activities($refresh = FALSE) {
    if (!isset($this->strength_training_activities) || $refresh) {
      $data = $this->request($this->uri->strength_training_activities, StrengthTrainingActivityFeed::TYPE);
      $this->strength_training_activities = new StrengthTrainingActivityFeed($data);
    }
    return $this->strength_training_activities;
  }

  public function background_activities($refresh = FALSE) {
    if (!isset($this->background_activities) || $refresh) {
      $data = $this->request($this->uri->background_activities, BackgroundActivitySetFeed::TYPE);
      $this->background_activities = new BackgroundActivitySetFeed($data);
    }
    return $this->background_activities;
  }

  public function sleep($refresh = FALSE) {
    if (!isset($this->sleep) || $refresh) {
      $data = $this->request($this->uri->sleep, SleepSetFeed::TYPE);
      $this->sleep = new SleepSetFeed($data);
    }
    return $this->sleep;
  }

  public function nutrition($refresh = FALSE) {
    if (!isset($this->nutrition) || $refresh) {
      $data = $this->request($this->uri->nutrition, NutritionSetFeed::TYPE);
      $this->nutrition = new NutritionSetFeed($data);
    }
    return $this->nutrition;
  }

  public function weight($refresh = FALSE) {
    if (!isset($this->weight) || $refresh) {
      $data = $this->request($this->uri->weight, WeightSetFeed::TYPE);
      $this->weight = new WeightSetFeed($data);
    }
    return $this->weight;
  }

  public function general_measurements($refresh = FALSE) {
    if (!isset($this->general_measurements) || $refresh) {
      $data = $this->request($this->uri->general_measurements, GeneralMeasurementSetFeed::TYPE);
      $this->general_measurements = new GeneralMeasurementSetFeed($data);
    }
    return $this->general_measurements;
  }

  public function diabetes($refresh = FALSE) {
    if (!isset($this->diabetes) || $refresh) {
      $data = $this->request($this->uri->diabetes, DiabetesMeasurementSetFeed::TYPE);
      $this->diabetes = new DiabetesMeasurementSetFeed($data);
    }
    return $this->diabetes;
  }

  public function records() {
    $data = $this->request($this->uri->records, Records::TYPE);
    $this->records = new Records($data);
    return $this->records;
  }

  public function team() {
    $data = $this->request($this->uri->team, TeamFeed::TYPE);
    $this->team = new TeamFeed($data);
    return $this->team;
  }

  public function change_log() {
    $data = $this->request($this->uri->change_log, ChangeLog::TYPE);
    $this->change_log = new ChangeLog($data);
    return $this->change_log;
  }

}

class Profile {

  const TYPE = 'application/vnd.com.runkeeper.Profile+json';

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

  public function __construct($data) {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

}

class Settings {

  const TYPE = 'application/vnd.com.runkeeper.Settings+json';

  public function __construct($data) {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

}

class FitnessActivityFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.FitnessActivityFeed+json';

  public function __construct($data) {
    $this->defaults($data);
    foreach ($this->items as &$item) {
      $item->start_time = strtotime($item->start_time);
      $item->total_calories = (isset($item->total_calories)) ? $item->total_calories : NULL;
    }
  }

}

class StrengthTrainingActivityFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.StrengthTrainingActivityFeed+json';

  public function __construct($data) {
    $this->defaults($data);
  }

}

class BackgroundActivitySetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.BackgroundActivitySetFeed+json';

  public function __construct($data) {
    $this->defaults($data);
  }

}

class SleepSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.SleepSetFeed+json';

  public function __construct($data) {
    $this->defaults($data);
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

  public function __construct($data) {
    $this->defaults($data);
  }

}

class WeightSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.WeightSetFeed+json';

  public function __construct($data) {
    $this->defaults($data);
  }

}

class GeneralMeasurementSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.GeneralMeasurementSetFeed+json';

  public function __construct($data) {
    $this->defaults($data);
  }

}

class DiabetesMeasurementSetFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.DiabetesMeasurementSetFeed+json';

  public function __construct($data) {
    $this->defaults($data);
  }

}

class Records extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.Records+json';

  public function __construct($data) {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

}

class TeamFeed extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.TeamFeed+json';

  public function __construct($data) {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

}

class ChangeLog extends \HealthGraph\Feed {

  const TYPE = 'application/vnd.com.runkeeper.ChangeLog+json';

  public function __construct($data) {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

}

?>

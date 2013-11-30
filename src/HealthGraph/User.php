<?php

namespace HealthGraph;

class User {

  const TYPE = 'application/vnd.com.runkeeper.User+json';

  public $userID;
  private $client;
  private $uri;

  public function __construct($access_token, $token_type = 'Bearer') {
    $this->uri = new \stdClass();
    $this->uri->user = '/user';
    $this->client = new Client();
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

  public function records($refresh = FALSE) {
    if (!isset($this->records) || $refresh) {
      $this->records = new Records($this->client, $this->uri->records);
    }
    return $this->records;
  }

  public function team($refresh = FALSE) {
    if (!isset($this->team) || $refresh) {
      $this->team = new TeamFeed($this->client, $this->uri->team);
    }
    return $this->team;
  }

  public function change_log($refresh = FALSE) {
    if (!isset($this->change_log) || $refresh) {
      $this->change_log = new ChangeLog($this->client, $this->uri->change_log);
    }
    return $this->change_log;
  }

}

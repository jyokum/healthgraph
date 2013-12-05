<?php

namespace HealthGraph\Model;

use Guzzle\Service\Resource\ResourceIterator;

class FeedIterator extends ResourceIterator {

  protected function sendRequest() {
    // If a next token is set, then add it to the command
    if ($this->nextToken) {
      $this->command->set('next_user', $this->nextToken);
    }

    // Execute the command and parse the result
    $result = $this->command->execute();

    // Parse the next token
    $this->nextToken = isset($result['next']) ? $result['next'] : false;

    return $result['items'];
  }

}
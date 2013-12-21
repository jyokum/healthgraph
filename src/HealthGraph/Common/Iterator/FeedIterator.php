<?php

namespace HealthGraph\Common\Iterator;

use Guzzle\Service\Resource\ResourceIterator;

class FeedIterator extends ResourceIterator
{

    protected function sendRequest()
    {
        // Execute the command and parse the result
        $result = $this->command->execute();

        // Parse the next token
        $this->nextToken = isset($result['next']) ? $result['next'] : false;

        return $result['items'];
    }
}

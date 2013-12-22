<?php

namespace HealthGraph\Tests\Operations;

class StrengthTrainingActivityFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'GetStrengthTrainingActivityFeed';
    }

}

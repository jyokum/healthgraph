<?php

namespace HealthGraph\Tests\Operations;

class FitnessActivityFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'GetFitnessActivityFeed';
    }

}

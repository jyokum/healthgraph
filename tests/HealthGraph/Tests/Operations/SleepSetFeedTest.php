<?php

namespace HealthGraph\Tests\Operations;

class SleepSetFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'GetSleepSetFeed';
    }

}

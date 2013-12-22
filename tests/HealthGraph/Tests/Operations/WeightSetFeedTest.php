<?php

namespace HealthGraph\Tests\Operations;

class WeightSetFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'GetWeightSetFeed';
    }

}

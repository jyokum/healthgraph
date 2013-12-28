<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group fitness
 * @group feed
 */
class FitnessActivityFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'FitnessActivityFeed';
    }

}

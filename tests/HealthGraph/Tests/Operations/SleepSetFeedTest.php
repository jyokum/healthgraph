<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group sleep
 * @group feed
 */
class SleepSetFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'SleepSetFeed';
    }

}

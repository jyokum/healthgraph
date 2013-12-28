<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group weight
 * @group feed
 */
class WeightSetFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'WeightSetFeed';
    }

}

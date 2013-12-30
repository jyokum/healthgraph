<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group diabetes
 * @group feed
 */
class DiabetesMeasurementSetFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'DiabetesMeasurementSetFeed';
    }

}

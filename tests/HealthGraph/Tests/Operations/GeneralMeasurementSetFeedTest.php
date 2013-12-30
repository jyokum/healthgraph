<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group general_measurements
 * @group feed
 */
class GeneralMeasurementSetFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'GeneralMeasurementSetFeed';
    }

}

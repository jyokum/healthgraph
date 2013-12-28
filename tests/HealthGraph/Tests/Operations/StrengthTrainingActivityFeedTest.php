<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group strength
 * @group feed
 */
class StrengthTrainingActivityFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'StrengthTrainingActivityFeed';
    }

}

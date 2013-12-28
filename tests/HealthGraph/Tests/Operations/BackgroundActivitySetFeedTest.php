<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group background
 * @group feed
 */
class BackgroundActivitySetFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'BackgroundActivitySetFeed';
    }

}

<?php

namespace HealthGraph\Tests\Operations;

class BackgroundActivitySetFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'GetBackgroundActivitySetFeed';
    }

}

<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group friend
 * @group feed
 */
class FriendFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'FriendFeed';
    }

}

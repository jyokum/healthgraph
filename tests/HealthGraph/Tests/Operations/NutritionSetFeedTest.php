<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group nutrition
 * @group feed
 */
class NutritionSetFeedTest extends BaseFeedTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'NutritionSetFeed';
    }

}

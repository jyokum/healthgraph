<?php

namespace HealthGraph\Tests\Operations;

class BackgroundActivitySetTest extends BaseTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'BackgroundActivitySet';
    }

}

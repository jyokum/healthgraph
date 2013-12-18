<?php

namespace HealthGraph\Tests;

use HealthGraph\HealthGraphClient;

class HealthGraphClientTest extends \Guzzle\Tests\GuzzleTestCase
{

    public function testBuilderCreatesClient()
    {
        $client = $this->getServiceBuilder()->get('client');
        $this->assertEquals('https://api.runkeeper.com', $client->getBaseUrl());
    }

    /**
     * @covers HealthGraph\HealthGraphClient::factory
     */
    public function testFactoryInitializesClient()
    {
        $client = HealthGraphClient::factory();
        $this->assertEquals('https://api.runkeeper.com', $client->getBaseUrl());
    }

}

<?php

namespace HealthGraph\Tests;

use HealthGraph\HealthGraphClient;

class HealthGraphClientTest extends \Guzzle\Tests\GuzzleTestCase
{

    protected function setUp()
    {
        if ($GLOBALS['access_token'] == '') {
            $this->access_token = substr(md5(uniqid('nonce_', true)), 0, 32);
        } else {
            $this->access_token = $GLOBALS['access_token'];
        }
        if ($GLOBALS['token_type'] == '') {
            $this->token_type = 'Bearer';
        } else {
            $this->token_type = $GLOBALS['token_type'];
        }
    }

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

    public function testGetUser()
    {
        $client = $this->getServiceBuilder()->get('client');
        $this->setMockResponse($client, 'user');
        $response = $client->getUser(array(
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
        ));
        $this->assertEquals('12345678', $response['userID']);
    }

    /**
     * @expectedException Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage Validation errors: [access_token] is a required string
     */
    public function testGetUserWithoutParams()
    {
        $client = $this->getServiceBuilder()->get('client');
        $this->setMockResponse($client, 'user');
        $response = $client->getUser();
    }

}

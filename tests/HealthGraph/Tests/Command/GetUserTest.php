<?php

namespace HealthGraph\Tests\Command;

use HealthGraph\HealthGraphClient;

class GetUserTest extends \Guzzle\Tests\GuzzleTestCase
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

    public function testGetUserIsCommand()
    {
        $client = $this->getServiceBuilder()->get('client');
        $command = $client->getCommand('GetUser');
        $this->assertInstanceOf('\Guzzle\Service\Command\AbstractCommand', $command);
    }

    /**
     * @covers HealthGraph\Command\GetUser::build
     * @covers HealthGraph\Command\GetUser::execute
     */
    public function testGetUser()
    {
        $client = $this->getServiceBuilder()->get('client');
        $this->setMockResponse($client, 'user');
        $response = $client->getUser(array(
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
        ));
        $this->assertEquals('12345678', $response['userID']);
        $this->assertEquals('12345678', $client->getConfig('hg.userID'));
        $this->assertEquals('/settings', $client->getConfig('hg.settings'));
    }

    /**
     * @expectedException Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage [access_token] is a required string
     */
    public function testGetUserWithoutParams()
    {
        $client = $this->getServiceBuilder()->get('client');
        $this->setMockResponse($client, 'user');
        $response = $client->getUser();
    }

    /**
     * @expectedException Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage [access_token] is a required string
     */
    public function testGetUserWithoutAccessToken()
    {
        $client = $this->getServiceBuilder()->get('client');
        $this->setMockResponse($client, 'user');
        $response = $client->getUser(array('token_type' => 'Bearer'));
    }

    public function testGetUserWithoutTokenType()
    {
        $client = $this->getServiceBuilder()->get('client');
        $this->setMockResponse($client, 'user');
        $response = $client->getUser(array('access_token' => 'foo'));
    }

}

<?php

namespace HealthGraph\Tests\Operations;

class ProfileTest extends \Guzzle\Tests\GuzzleTestCase
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
    }

    public function testGetCommandIsValid()
    {
        $object = $this->client->getCommand('GetProfile');
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $object);
    }

    public function testUpdateCommandIsValid()
    {
        $object = $this->client->getCommand('UpdateProfile');
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $object);
    }

    public function testPrepareGetCommand()
    {
        $command = $this->client->getCommand('GetProfile');
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    public function testPrepareUpdateCommand()
    {
        $command = $this->client->getCommand('UpdateProfile');
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    public function testValidAthleteType()
    {
        $command = $this->client->getCommand('UpdateProfile', array('athlete_type' => 'Runner'));
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    /**
     * @expectedException \Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage Validation errors: [athlete_type] must be one of "Athlete" or "Runner" or "Marathoner" or "Ultra Marathoner" or "Cyclist" or "Tri-Athlete" or "Walker" or "Hiker" or "Skier" or "Snowboarder" or "Skater" or "Swimmer" or "Rower"
     */
    public function testInvalidAthleteType()
    {
        $command = $this->client->getCommand('UpdateProfile', array('athlete_type' => 'Foo'));
        $command->prepare();
    }

}

<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group settings
 */
class SettingsTest extends \Guzzle\Tests\GuzzleTestCase
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
    }

    public function testGetCommandIsValid()
    {
        $object = $this->client->getCommand('GetSettings');
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $object);
    }

    public function testUpdateCommandIsValid()
    {
        $object = $this->client->getCommand('UpdateSettings');
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $object);
    }

    public function testPrepareGetCommand()
    {
        $command = $this->client->getCommand('GetSettings');
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    public function testPrepareUpdateCommand()
    {
        $command = $this->client->getCommand('UpdateSettings');
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    public function testValidShareFitnessActivities()
    {
        $command = $this->client->getCommand('UpdateSettings', array('share_fitness_activities' => 'Just Me'));
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    /**
     * @expectedException \Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage Validation errors: [share_fitness_activities] must be one of "Just Me" or "Friends" or "Everyone"
     */
    public function testInvalidShareFitnessActivities()
    {
        $command = $this->client->getCommand('UpdateSettings', array('share_fitness_activities' => 'Foo'));
        $command->prepare();
    }

}

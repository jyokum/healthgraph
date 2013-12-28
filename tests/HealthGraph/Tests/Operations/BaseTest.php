<?php

namespace HealthGraph\Tests\Operations;

abstract class BaseTest extends \Guzzle\Tests\GuzzleTestCase
{

    protected $client;
    protected $base;

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
    }

    public function testOperationsExist()
    {
        $desc = $this->client->getDescription();
        $this->assertTrue($desc->hasOperation('Get' . $this->base));
        $this->assertTrue($desc->hasOperation('New' . $this->base));
        $this->assertTrue($desc->hasOperation('Update' . $this->base));
        $this->assertTrue($desc->hasOperation('Delete' . $this->base));
    }

    public function testGetCommandIsValid()
    {
        $command = $this->client->getCommand('Get' . $this->base);
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $command);
    }

    public function testGetResponseIsExpected()
    {
        $command = $this->client->getCommand('Get' . $this->base);
        $op = $this->readAttribute($command, 'operation');
        $this->assertEquals($this->base, $op->getResponseClass());
    }

    public function testPrepareGetCommand()
    {
        $command = $this->client->getCommand('Get' . $this->base, array('uri' => '/foo'));
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    /**
     * @expectedException Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage [uri] is a required string
     */
    public function testPrepareGetCommandRequired()
    {
        $command = $this->client->getCommand('Get' . $this->base);
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    public function testNewCommandIsValid()
    {
        $command = $this->client->getCommand('New' . $this->base);
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $command);
    }

    public function testNewResponseIsExpected()
    {
        $command = $this->client->getCommand('New' . $this->base);
        $op = $this->readAttribute($command, 'operation');
        $this->assertEquals('GenericOutput', $op->getResponseClass());
    }

    public function testPrepareNewCommand()
    {
        $command = $this->client->getCommand('New' . $this->base);
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    public function testUpdateCommandIsValid()
    {
        $command = $this->client->getCommand('Update' . $this->base);
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $command);
    }

    public function testUpdateResponseIsExpected()
    {
        $command = $this->client->getCommand('Update' . $this->base);
        $op = $this->readAttribute($command, 'operation');
        $this->assertEquals($this->base, $op->getResponseClass());
    }

    public function testPrepareUpdateCommand()
    {
        $command = $this->client->getCommand('Update' . $this->base, array('uri' => '/foo'));
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    /**
     * @expectedException Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage [uri] is a required string
     */
    public function testPrepareUpdateCommandRequired()
    {
        $command = $this->client->getCommand('Update' . $this->base);
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    public function testDeleteCommandIsValid()
    {
        $command = $this->client->getCommand('Delete' . $this->base);
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $command);
    }

    public function testDeleteResponseIsExpected()
    {
        $command = $this->client->getCommand('Delete' . $this->base);
        $op = $this->readAttribute($command, 'operation');
        $this->assertEquals('GenericOutput', $op->getResponseClass());
    }

    public function testPrepareDeleteCommand()
    {
        $command = $this->client->getCommand('Delete' . $this->base, array('uri' => '/foo'));
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    /**
     * @expectedException Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage [uri] is a required string
     */
    public function testPrepareDeleteCommandRequired()
    {
        $command = $this->client->getCommand('Delete' . $this->base);
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

}

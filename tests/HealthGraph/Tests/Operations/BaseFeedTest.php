<?php

namespace HealthGraph\Tests\Operations;

abstract class BaseFeedTest extends \Guzzle\Tests\GuzzleTestCase
{
    protected $client;
    protected $base;

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
    }

    public function testFeedOperationsAreDefined()
    {
        $desc = $this->client->getDescription();
        $this->assertTrue($desc->hasOperation('Get' . $this->base));
    }

    public function testFeedModelsAreDefined()
    {
        $desc = $this->client->getDescription();
        $this->assertTrue($desc->hasModel($this->base . 'Response'));
        $this->assertTrue($desc->hasModel($this->base . 'Item'));
    }

    public function testCommandIsValid()
    {
        $object = $this->client->getCommand('Get' . $this->base);
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $object);
    }

    public function testFeedReturnsExpectedResponseClass()
    {
        $command = $this->client->getCommand('Get' . $this->base);
        $op = $this->readAttribute($command, 'operation');
        $this->assertEquals($this->base . 'Response', $op->getResponseClass());
    }

    public function testIteratorIsValid()
    {
        $object = $this->client->getIterator('Get' . $this->base);
        $this->assertInstanceOf('\HealthGraph\Common\Iterator\FeedIterator', $object);
    }

    public function testPrepareGetCommand()
    {
        $command = $this->client->getCommand('Get' . $this->base);
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

}

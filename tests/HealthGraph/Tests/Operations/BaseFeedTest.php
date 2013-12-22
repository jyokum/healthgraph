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

    public function testCommandIsValid()
    {
        $object = $this->client->getCommand($this->base);
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $object);
    }

    public function testIteratorIsValid()
    {
        $object = $this->client->getIterator($this->base);
        $this->assertInstanceOf('\HealthGraph\Common\Iterator\FeedIterator', $object);
    }

    public function testPrepareGetCommand()
    {
        $command = $this->client->getCommand($this->base);
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

}

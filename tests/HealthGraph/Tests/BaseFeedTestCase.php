<?php

namespace HealthGraph\Tests\Operations;

class BaseFeedTestCase extends \Guzzle\Tests\GuzzleTestCase
{
    protected $operation;

    public function testCommandIsValid()
    {
        $object = $this->client->getCommand($this->operation);
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $object);
    }

    public function testIteratorIsValid()
    {
        $object = $this->client->getIterator($this->operation);
        $this->assertInstanceOf('\HealthGraph\Common\Iterator\FeedIterator', $object);
    }

    public function testPrepareGetCommand()
    {
        $command = $this->client->getCommand($this->operation);
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

}

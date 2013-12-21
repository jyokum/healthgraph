<?php

namespace HealthGraph\Tests\Operations;

class FitnessActivityFeedTest extends \Guzzle\Tests\GuzzleTestCase
{
    const OPERATION = 'GetFitnessActivityFeed';

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
    }

    public function testCommandIsValid()
    {
        $object = $this->client->getCommand(self::OPERATION);
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $object);
    }

    public function testIteratorIsValid()
    {
        $object = $this->client->getIterator(self::OPERATION);
        $this->assertInstanceOf('\HealthGraph\Common\Iterator\FeedIterator', $object);
    }

    public function testPrepareGetCommand()
    {
        $command = $this->client->getCommand(self::OPERATION);
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

}

<?php

namespace HealthGraph\Tests\Common\Iterator;

use HealthGraph\Common\Iterator\HealthGraphIteratorFactory;
use HealthGraph\Common\Iterator\FeedIterator;
use Guzzle\Tests\Service\Mock\Command\MockCommand;

class FeedIteratorTest extends \Guzzle\Tests\GuzzleTestCase
{
    /**
     * @covers HealthGraph\Common\Iterator\FeedIterator
     */
    public function testClass()
    {
        $command = new MockCommand;
        $iterator = new FeedIterator($command);
        $this->assertInstanceOf('\Guzzle\Service\Resource\ResourceIterator', $iterator);
    }

    /**
     * @covers HealthGraph\Common\Iterator\FeedIterator::sendRequest
     */
    public function testIterateAll()
    {
        $client = $this->getServiceBuilder()->get('client');
        $this->setMockResponse($client, array('feed','feed_next'));
        $command = $client->getCommand('abstract.Feed');
        $iterator = new FeedIterator($command);
        $this->assertInstanceOf('HealthGraph\Common\Iterator\FeedIterator', $iterator);

        $counter = 0;
        foreach ($iterator as $item) {
            $counter++;
            if ($counter <= 4) {
                $this->assertEquals('http://example.com?next', $iterator->getNextToken());
            }
            else {
                $this->assertEquals(0, $iterator->getNextToken());
            }
        }
        $this->assertEquals(8, $counter);
    }

    /**
     * @covers HealthGraph\Common\Iterator\FeedIterator::sendRequest
     */
    public function testLimitOnIterator()
    {
        $client = $this->getServiceBuilder()->get('client');
        $this->setMockResponse($client, 'feed');
        $command = $client->getCommand('abstract.Feed');
        $iterator = new FeedIterator($command);
        $this->assertInstanceOf('HealthGraph\Common\Iterator\FeedIterator', $iterator);

        $response = $iterator->setLimit(2)->toArray();
        $this->assertCount(2, $response);
    }

    /**
     * @covers HealthGraph\Common\Iterator\FeedIterator::sendRequest
     */
    public function testLimitInNext()
    {
        $client = $this->getServiceBuilder()->get('client');
        $this->setMockResponse($client, array('feed', 'feed_next'));
        $command = $client->getCommand('abstract.Feed');
        $iterator = new FeedIterator($command);
        $this->assertInstanceOf('HealthGraph\Common\Iterator\FeedIterator', $iterator);

        $response = $iterator->setLimit(6)->toArray();
        $this->assertCount(6, $response);
    }

}

<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 */
class FitnessTest extends \Guzzle\Tests\GuzzleTestCase
{

    protected static $client;

    protected function setUp()
    {
        // We're only going to create and prime the client once
        if (!isset(self::$client)) {
            self::$client = $this->getServiceBuilder()->get('client');
            self::$client->getUser(array(
                'access_token' => $GLOBALS['access_token'],
                'token_type' => $GLOBALS['token_type'],
            ));
        }
    }

    public function testUserIsLoaded()
    {
        $this->assertTrue(is_numeric(self::$client->getConfig('hg.userID')));
        $this->assertNotNull(self::$client->getConfig('hg.fitness_activities'));
    }

    public function testNewFitnessActivity()
    {
        $command = self::$client->getCommand('NewFitnessActivity', array(
            "type" => "Running",
            "start_time" => date(DATE_RFC1123),
            "duration" => rand(600, 10000),
            "total_distance" => (rand(10000, 200000) / 10),
            "notes" => "Unit test"
        ));
        $result = $command->execute();

        $this->assertNotNull($result->get('location'));
        $this->creations[] = $result->get('location');

        return $result->get('location');
    }

    /**
     * @depends testNewFitnessActivity
     */
    public function testGetFitnessActivitySummary($uri)
    {
        $command = self::$client->getCommand('GetFitnessActivitySummary', array('uri' => $uri));
        $result = $command->execute();
        $this->assertNotNull($result->get('uri'));
        $this->assertEquals($uri, $result->get('uri'));
        $this->assertEquals('Running', $result->get('type'));
        $this->assertEquals('Unit test', $result->get('notes'));
        $this->assertNull($result->get('path'));
    }

    /**
     * @depends testNewFitnessActivity
     */
    public function testGetFitnessActivity($uri)
    {
        $command = self::$client->getCommand('GetFitnessActivity', array('uri' => $uri));
        $result = $command->execute();
        $this->assertNotNull($result->get('uri'));
        $this->assertNotNull($result->get('path'));
    }

    /**
     * @depends testNewFitnessActivity
     */
    public function testUpdateFitnessActivity($uri)
    {
        $notes = 'Unit test updated';

        $command = self::$client->getCommand('UpdateFitnessActivity', array(
            "uri" => $uri,
            "notes" => $notes
        ));
        $result = $command->execute();

        $this->assertEquals($notes, $result->get('notes'));
    }

    public function testGetFitnessActivityFeed()
    {
        $command = self::$client->getIterator('GetFitnessActivityFeed')->setLimit(5);
        $result = $command->toArray();

        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertLessThanOrEqual(5, count($result));
    }

    public function testIterateFeedItems()
    {
        $feed = self::$client->getIterator('GetFitnessActivityFeed')->setLimit(5);
        foreach ($feed as $item) {
            $command = self::$client->getCommand('GetFitnessActivitySummary', array('uri' => $item['uri']));
            $result = $command->execute();
            $this->assertEquals($item['uri'], $result->get('uri'));
        }
    }

    /**
     * @depends testNewFitnessActivity
     */
    public function testDeleteFitnessActivity($uri)
    {
        $command = self::$client->getCommand('DeleteFitnessActivity', array('uri' => $uri));
        $result = $command->execute();
        $this->assertEquals(204, $result->get('status'));
    }

}

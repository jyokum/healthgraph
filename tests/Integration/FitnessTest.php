<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 */
class FitnessTest extends \Guzzle\Tests\GuzzleTestCase
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->client->getUser(array(
            'access_token' => $GLOBALS['access_token'],
            'token_type' => $GLOBALS['token_type'],
        ));
    }

    public function testUserIsLoaded()
    {
        $this->assertTrue(is_numeric($this->client->getConfig('hg.userID')));
        $this->assertNotNull($this->client->getConfig('hg.fitness_activities'));
    }

    public function testNewFitnessActivity()
    {
        $command = $this->client->getCommand('NewFitnessActivity', array(
            "type" => "Running",
            "start_time" => date(DATE_RFC1123),
            "duration" => rand(600, 10000),
            "total_distance" => (rand(10000, 200000) / 10),
            "notes" => "Unit test"
        ));
        $result = $command->execute();

        $this->assertNotNull($result->get('location'));

        return $result->get('location');
    }

    /**
     * @depends testNewFitnessActivity
     */
    public function testGetFitnessActivitySummary($uri)
    {
        $command = $this->client->getCommand('GetFitnessActivitySummary', array('uri' => $uri));
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
        $command = $this->client->getCommand('GetFitnessActivity', array('uri' => $uri));
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

        $command = $this->client->getCommand('UpdateFitnessActivity', array(
            "uri" => $uri,
            "notes" => $notes
        ));
        $result = $command->execute();

        $this->assertEquals($notes, $result->get('notes'));
    }

    /**
     * @depends testNewFitnessActivity
     */
    public function testGetFitnessActivityFeed($uri)
    {
        $command = $this->client->getIterator('GetFitnessActivityFeed')->setLimit(5);
        $result = $command->toArray();

        $this->assertLessThanOrEqual(5, count($result));
        $this->assertEquals($uri, $result[0]['uri']);
    }

    /**
     * @depends testNewFitnessActivity
     */
    public function testDeleteFitnessActivity($uri)
    {
        $command = $this->client->getCommand('DeleteFitnessActivity', array('uri' => $uri));
        $result = $command->execute();
        $this->assertEquals(204, $result->get('status'));
    }

}

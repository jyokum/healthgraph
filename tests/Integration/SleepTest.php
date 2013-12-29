<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group background
 */
class SleepTest extends \Guzzle\Tests\GuzzleTestCase
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
        $this->assertNotNull(self::$client->getConfig('hg.background_activities'));
    }

    public function testNewSleepSet()
    {
        $command = self::$client->getCommand('NewSleepSet', array(
            'timestamp' => date(DATE_RFC1123),
            'total_sleep' => 300,
        ));
        $result = $command->execute();

        $this->assertNotNull($result->get('location'));

        return $result->get('location');
    }

    /**
     * @depends testNewSleepSet
     */
    public function testGetSleepSet($uri)
    {
        $command = self::$client->getCommand('GetSleepSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertNotNull($result->get('uri'));
        $this->assertEquals(300, $result->get('total_sleep'));
    }

    /**
     * @depends testNewSleepSet
     */
    public function testUpdateSleepSet($uri)
    {
        $command = self::$client->getCommand('UpdateSleepSet', array(
            "uri" => $uri,
            "total_sleep" => 250
        ));
        $result = $command->execute();

        $this->assertEquals(250, $result->get('total_sleep'));
    }

    public function testGetSleepSetFeed()
    {
        $command = self::$client->getIterator('GetSleepSetFeed')->setLimit(5);
        $result = $command->toArray();

        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertLessThanOrEqual(5, count($result));
    }

    public function testIterateFeedItems()
    {
        $feed = self::$client->getIterator('GetSleepSetFeed')->setLimit(5);
        foreach ($feed as $item) {
            $command = self::$client->getCommand('GetSleepSet', array('uri' => $item['uri']));
            $result = $command->execute();
            $this->assertEquals($item['uri'], $result->get('uri'));
        }
    }

    /**
     * @depends testNewSleepSet
     */
    public function testDeleteSleepSet($uri)
    {
        $command = self::$client->getCommand('DeleteSleepSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertEquals(204, $result->get('status'));
    }

}

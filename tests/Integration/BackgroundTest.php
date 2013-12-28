<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group background
 */
class BackgroundTest extends \Guzzle\Tests\GuzzleTestCase
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

    public function testNewBackgroundActivitySet()
    {
        $command = self::$client->getCommand('NewBackgroundActivitySet', array(
            'timestamp' => date(DATE_RFC1123),
            'steps' => 200,
        ));
        $result = $command->execute();

        $this->assertNotNull($result->get('location'));

        return $result->get('location');
    }

    /**
     * @depends testNewBackgroundActivitySet
     */
    public function testGetBackgroundActivitySet($uri)
    {
        $command = self::$client->getCommand('GetBackgroundActivitySet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertNotNull($result->get('uri'));
        $this->assertEquals(200, $result->get('steps'));
    }

    /**
     * @depends testNewBackgroundActivitySet
     */
    public function testUpdateBackgroundActivitySet($uri)
    {
        $command = self::$client->getCommand('UpdateBackgroundActivitySet', array(
            "uri" => $uri,
            "steps" => 250
        ));
        $result = $command->execute();

        $this->assertEquals(250, $result->get('steps'));
    }

    public function testGetBackgroundActivitySetFeed()
    {
        $command = self::$client->getIterator('GetBackgroundActivitySetFeed')->setLimit(5);
        $result = $command->toArray();

        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertLessThanOrEqual(5, count($result));
    }

    public function testIterateFeedItems()
    {
        $feed = self::$client->getIterator('GetBackgroundActivitySetFeed')->setLimit(5);
        foreach ($feed as $item) {
            $command = self::$client->getCommand('GetBackgroundActivitySet', array('uri' => $item['uri']));
            $result = $command->execute();
            $this->assertEquals($item['uri'], $result->get('uri'));
        }
    }

    /**
     * @depends testNewBackgroundActivitySet
     */
    public function testDeleteBackgroundActivitySet($uri)
    {
        $command = self::$client->getCommand('DeleteBackgroundActivitySet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertEquals(204, $result->get('status'));
    }

}

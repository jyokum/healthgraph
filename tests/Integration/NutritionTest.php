<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 */
class NutritionTest extends \Guzzle\Tests\GuzzleTestCase
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
        $this->assertNotNull(self::$client->getConfig('hg.nutrition'));
    }

    public function testNewNutritionSet()
    {
        $command = self::$client->getCommand('NewNutritionSet', array(
            'timestamp' => date(DATE_RFC1123),
            'calories' => 200,
        ));
        $result = $command->execute();

        $this->assertNotNull($result->get('location'));

        return $result->get('location');
    }

    /**
     * @depends testNewNutritionSet
     */
    public function testGetNutritionActivity($uri)
    {
        $command = self::$client->getCommand('GetNutritionSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertNotNull($result->get('uri'));
        $this->assertEquals(200, $result->get('calories'));
    }

    /**
     * @depends testNewNutritionSet
     */
    public function testUpdateNutritionSet($uri)
    {
        $command = self::$client->getCommand('UpdateNutritionSet', array(
            "uri" => $uri,
            "calories" => 250
        ));
        $result = $command->execute();

        $this->assertEquals(250, $result->get('calories'));
    }

    public function testGetNutritionSetFeed()
    {
        $command = self::$client->getIterator('GetNutritionSetFeed')->setLimit(5);
        $result = $command->toArray();

        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertLessThanOrEqual(5, count($result));
    }

    public function testIterateFeedItems()
    {
        $feed = self::$client->getIterator('GetNutritionSetFeed')->setLimit(5);
        foreach ($feed as $item) {
            $command = self::$client->getCommand('GetNutritionSet', array('uri' => $item['uri']));
            $result = $command->execute();
            $this->assertEquals($item['uri'], $result->get('uri'));
        }
    }

    /**
     * @depends testNewNutritionSet
     */
    public function testDeleteNutritionSet($uri)
    {
        $command = self::$client->getCommand('DeleteNutritionSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertEquals(204, $result->get('status'));
    }

}

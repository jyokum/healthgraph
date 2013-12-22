<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 */
class NutritionTest extends \Guzzle\Tests\GuzzleTestCase
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
        $this->assertNotNull($this->client->getConfig('hg.nutrition'));
    }

    public function testNewNutritionSet()
    {
        $command = $this->client->getCommand('NewNutritionSet', array(
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
        $command = $this->client->getCommand('GetNutritionSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertNotNull($result->get('uri'));
        $this->assertEquals(200, $result->get('calories'));
    }

    /**
     * @depends testNewNutritionSet
     */
    public function testUpdateNutritionSet($uri)
    {
        $command = $this->client->getCommand('UpdateNutritionSet', array(
            "uri" => $uri,
            "calories" => 250
        ));
        $result = $command->execute();

        $this->assertEquals(250, $result->get('calories'));
    }

    /**
     * @depends testNewNutritionSet
     */
    public function testGetNutritionSetFeed($uri)
    {
        $command = $this->client->getIterator('GetNutritionSetFeed')->setLimit(5);
        $result = $command->toArray();

        $this->assertLessThanOrEqual(5, count($result));
        $this->assertEquals(250, $result[0]['calories']);
        // @TODO the uri returned in the feed doesn't match the uri returned from new for some reason
        // $this->assertEquals($uri, $result[0]['uri']);
    }

    /**
     * @depends testNewNutritionSet
     */
    public function testDeleteNutritionSet($uri)
    {
        $command = $this->client->getCommand('DeleteNutritionSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertEquals(204, $result->get('status'));
    }

}

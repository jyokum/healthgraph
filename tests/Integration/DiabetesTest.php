<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group diabetes
 */
class DiabetesTest extends \Guzzle\Tests\GuzzleTestCase
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
        $this->assertNotNull(self::$client->getConfig('hg.diabetes'));
    }

    public function testNewDiabetesMeasurementSet()
    {
        $command = self::$client->getCommand('NewDiabetesMeasurementSet', array(
            'timestamp' => date(DATE_RFC1123),
            'insulin' => 1,
        ));
        $result = $command->execute();

        $this->assertNotNull($result->get('location'));

        return $result->get('location');
    }

    /**
     * @depends testNewDiabetesMeasurementSet
     */
    public function testGetDiabetesMeasurementSet($uri)
    {
        $command = self::$client->getCommand('GetDiabetesMeasurementSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertNotNull($result->get('uri'));
        $this->assertEquals(1, $result->get('insulin'));
    }

    /**
     * @depends testNewDiabetesMeasurementSet
     */
    public function testUpdateDiabetesMeasurementSet($uri)
    {
        $command = self::$client->getCommand('UpdateDiabetesMeasurementSet', array(
            "uri" => $uri,
            "insulin" => 1.12
        ));
        $result = $command->execute();

        $this->assertEquals(1.12, $result->get('insulin'));
    }

    public function testGetDiabetesMeasurementSetFeed()
    {
        $command = self::$client->getIterator('GetDiabetesMeasurementSetFeed')->setLimit(5);
        $result = $command->toArray();

        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertLessThanOrEqual(5, count($result));
    }

    public function testIterateFeedItems()
    {
        $feed = self::$client->getIterator('GetDiabetesMeasurementSetFeed')->setLimit(5);
        foreach ($feed as $item) {
            $command = self::$client->getCommand('GetDiabetesMeasurementSet', array('uri' => $item['uri']));
            $result = $command->execute();
            $this->assertEquals($item['uri'], $result->get('uri'));
        }
    }

    /**
     * @depends testNewDiabetesMeasurementSet
     */
    public function testDeleteDiabetesMeasurementSet($uri)
    {
        $command = self::$client->getCommand('DeleteDiabetesMeasurementSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertEquals(204, $result->get('status'));
    }

}

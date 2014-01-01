<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group weight
 */
class WeightTest extends BaseIntegrationTest
{

    public function testUserIsLoaded()
    {
        $this->assertTrue(is_numeric(self::$client->getConfig('hg.userID')));
        $this->assertNotNull(self::$client->getConfig('hg.weight'));
    }

    public function testNewWeightSet()
    {
        $command = self::$client->getCommand('NewWeightSet', array(
            'timestamp' => date(DATE_RFC1123),
            'weight' => 80,
        ));
        $result = $command->execute();

        $this->assertNotNull($result->get('location'));
        self::$creations[] = $result->get('location');

        return $result->get('location');
    }

    /**
     * @depends testNewWeightSet
     */
    public function testGetWeightSet($uri)
    {
        $command = self::$client->getCommand('GetWeightSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertNotNull($result->get('uri'));
        $this->assertEquals(80, $result->get('weight'));
    }

    /**
     * @depends testNewWeightSet
     */
    public function testUpdateWeightSet($uri)
    {
        $command = self::$client->getCommand('UpdateWeightSet', array(
            "uri" => $uri,
            "weight" => 79.38
        ));
        $result = $command->execute();

        $this->assertEquals(79.38, $result->get('weight'));
    }

    public function testGetWeightSetFeed()
    {
        $command = self::$client->getIterator('GetWeightSetFeed')->setLimit(5);
        $result = $command->toArray();

        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertLessThanOrEqual(5, count($result));
    }

    public function testIterateFeedItems()
    {
        $feed = self::$client->getIterator('GetWeightSetFeed')->setLimit(5);
        foreach ($feed as $item) {
            $command = self::$client->getCommand('GetWeightSet', array('uri' => $item['uri']));
            $result = $command->execute();
            $this->assertEquals($item['uri'], $result->get('uri'));
        }
    }

    /**
     * @depends testNewWeightSet
     */
    public function testDeleteWeightSet($uri)
    {
        $command = self::$client->getCommand('DeleteWeightSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertEquals(204, $result->get('status'));
    }

}

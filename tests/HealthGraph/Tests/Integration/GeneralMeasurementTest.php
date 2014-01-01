<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group general_measurements
 */
class GeneralMeasurementTest extends BaseIntegrationTest
{

    public function testUserIsLoaded()
    {
        $this->assertTrue(is_numeric(self::$client->getConfig('hg.userID')));
        $this->assertNotNull(self::$client->getConfig('hg.general_measurements'));
    }

    public function testNewGeneralMeasurementSet()
    {
        $command = self::$client->getCommand('NewGeneralMeasurementSet', array(
            'timestamp' => date(DATE_RFC1123),
            'total_cholesterol' => 190,
        ));
        $result = $command->execute();

        $this->assertNotNull($result->get('location'));
        self::$creations[] = $result->get('location');

        return $result->get('location');
    }

    /**
     * @depends testNewGeneralMeasurementSet
     */
    public function testGetGeneralMeasurementSet($uri)
    {
        $command = self::$client->getCommand('GetGeneralMeasurementSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertNotNull($result->get('uri'));
        $this->assertEquals(190, $result->get('total_cholesterol'));
    }

    /**
     * @depends testNewGeneralMeasurementSet
     */
    public function testUpdateGeneralMeasurementSet($uri)
    {
        $command = self::$client->getCommand('UpdateGeneralMeasurementSet', array(
            "uri" => $uri,
            "total_cholesterol" => 195
        ));
        $result = $command->execute();

        $this->assertEquals(195, $result->get('total_cholesterol'));
    }

    public function testGetGeneralMeasurementSetFeed()
    {
        $command = self::$client->getIterator('GetGeneralMeasurementSetFeed')->setLimit(5);
        $result = $command->toArray();

        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertLessThanOrEqual(5, count($result));
    }

    public function testIterateFeedItems()
    {
        $feed = self::$client->getIterator('GetGeneralMeasurementSetFeed')->setLimit(5);
        foreach ($feed as $item) {
            $command = self::$client->getCommand('GetGeneralMeasurementSet', array('uri' => $item['uri']));
            $result = $command->execute();
            $this->assertEquals($item['uri'], $result->get('uri'));
        }
    }

    /**
     * @depends testNewGeneralMeasurementSet
     */
    public function testDeleteGeneralMeasurementSet($uri)
    {
        $command = self::$client->getCommand('DeleteGeneralMeasurementSet', array('uri' => $uri));
        $result = $command->execute();
        $this->assertEquals(204, $result->get('status'));
    }

}

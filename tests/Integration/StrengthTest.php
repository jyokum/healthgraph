<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group strength
 */
class StrengthTest extends \Guzzle\Tests\GuzzleTestCase
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
        $this->assertNotNull(self::$client->getConfig('hg.strength_training_activities'));
    }

    public function testNewStrengthTrainingActivity()
    {
        $command = self::$client->getCommand('NewStrengthTrainingActivity', array(
            "start_time" => date(DATE_RFC1123),
            "notes" => "Unit test",
            "exercises" => array(
                array(
                    'primary_type' => 'Dumbbell Tricep Press',
                    'notes' => 'Unit test exercise 1',
                    'primary_muscle_group' => 'Arms',
                    'sets' => array(
                        array('weight' => 15, 'repetitions' => 10),
                        array('weight' => 15, 'repetitions' => 10),
                        array('weight' => 15, 'repetitions' => 10),
                    )
                ),
                array(
                    'primary_type' => 'Dumbbell Curl',
                    'notes' => 'Unit test exercise 2',
                    'primary_muscle_group' => 'Arms',
                    'sets' => array(
                        array('weight' => 18, 'repetitions' => 10),
                        array('weight' => 18, 'repetitions' => 10),
                        array('weight' => 18, 'repetitions' => 10),
                    )
                )
            )
        ));
        $result = $command->execute();

        $this->assertNotNull($result->get('location'));

        return $result->get('location');
    }

    /**
     * @depends testNewStrengthTrainingActivity
     */
    public function testGetStrengthTrainingActivity($uri)
    {
        $command = self::$client->getCommand('GetStrengthTrainingActivity', array('uri' => $uri));
        $result = $command->execute();
        $this->assertNotNull($result->get('uri'));
        $this->assertEquals($uri, $result->get('uri'));
        $this->assertEquals('Unit test', $result->get('notes'));
    }

    /**
     * @depends testNewStrengthTrainingActivity
     */
    public function testUpdateStrengthTrainingActivity($uri)
    {
        $notes = 'Unit test updated';

        $command = self::$client->getCommand('UpdateStrengthTrainingActivity', array(
            "uri" => $uri,
            "notes" => $notes,
            "exercises" => array(
                array(
                    'primary_type' => 'Barbell Curl',
                    'notes' => 'Unit test exercise 3',
                    'primary_muscle_group' => 'Arms',
                    'sets' => array(
                        array('weight' => 32, 'repetitions' => 5),
                        array('weight' => 32, 'repetitions' => 5),
                        array('weight' => 32, 'repetitions' => 5),
                    )
                )
            )
        ));
        $result = $command->execute();

        $this->assertEquals($notes, $result->get('notes'));
        $this->assertCount(1, $result->get('exercises'));
        $this->assertEquals('Unit test exercise 3', $result->get('exercises')[0]['notes']);
    }

    /**
     * @depends testNewStrengthTrainingActivity
     */
    public function testGetStrengthTrainingActivityFeed($uri)
    {
        $command = self::$client->getIterator('GetStrengthTrainingActivityFeed')->setLimit(5);
        $result = $command->toArray();

        $this->assertLessThanOrEqual(5, count($result));
        $this->assertEquals($uri, $result[0]['uri']);
    }

    /**
     * @depends testNewStrengthTrainingActivity
     */
    public function testDeleteStrengthTrainingActivity($uri)
    {
        $command = self::$client->getCommand('DeleteStrengthTrainingActivity', array('uri' => $uri));
        $result = $command->execute();
        $this->assertEquals(204, $result->get('status'));
    }

}

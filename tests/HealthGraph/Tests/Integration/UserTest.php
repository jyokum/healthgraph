<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group user
 */
class UserTest extends \Guzzle\Tests\GuzzleTestCase
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
        $hg = array(
            'hg.strength_training_activities',
            'hg.weight',
            'hg.settings',
            'hg.diabetes',
            'hg.team',
            'hg.sleep',
            'hg.fitness_activities',
            'hg.change_log',
            'hg.nutrition',
            'hg.general_measurements',
            'hg.background_activities',
            'hg.records',
            'hg.profile',
        );

        $this->assertTrue(is_numeric($this->client->getConfig('hg.userID')));
        foreach ($hg as $value) {
            $this->assertNotNull($this->client->getConfig($value));
        }
    }

}

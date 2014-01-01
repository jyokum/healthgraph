<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group settings
 */
class SettingsTest extends \Guzzle\Tests\GuzzleTestCase
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
        $this->assertNotNull($this->client->getConfig('hg.settings'));
    }

    public function testGetSettings()
    {
        $profile = $this->client->getCommand('GetSettings')->execute();
        $this->assertNotNull($profile->get('share_fitness_activities'));
        $this->assertNotNull($profile->get('share_map'));
        $this->assertNull($profile->get('foo'));
    }

    public function testChangeShareFitnessActivities()
    {
        $options = array('Friends', 'Just Me');
        foreach ($options as $new_value) {
            $this->client->getCommand('UpdateSettings', array('share_fitness_activities' => $new_value))->execute();
            $result = $this->client->getCommand('GetSettings')->execute();
            $current_value = $result->get('share_fitness_activities');
            $this->assertEquals($new_value, $current_value);
        }
    }

}

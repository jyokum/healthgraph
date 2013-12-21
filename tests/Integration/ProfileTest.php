<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 */
class ProfileTest extends \Guzzle\Tests\GuzzleTestCase
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
        $this->assertNotNull($this->client->getConfig('hg.profile'));
    }

    public function testGetProfile()
    {
        $profile = $this->client->getCommand('GetProfile')->execute();
        $this->assertNotNull($profile->get('profile'));
        $this->assertNotNull($profile->get('elite'));
    }

    public function testChangeAthleteType()
    {
        $options = array('Runner', 'Athlete');
        foreach ($options as $new_value) {
            $this->client->getCommand('UpdateProfile', array('athlete_type' => $new_value))->execute();
            $result = $this->client->getCommand('GetProfile')->execute();
            $current_value = $result->get('athlete_type');
            $this->assertEquals($new_value, $current_value);
        }
    }

}

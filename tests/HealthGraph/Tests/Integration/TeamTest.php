<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group team
 */
class TeamTest extends BaseIntegrationTest
{

    public function testUserIsLoaded()
    {
        $this->assertTrue(is_numeric(self::$client->getConfig('hg.userID')));
        $this->assertNotNull(self::$client->getConfig('hg.team'));
    }

    public function testNewFriend()
    {
        if (isset($GLOBALS['test_friend']) && $GLOBALS['test_friend'] != '') {
            $friend = $GLOBALS['test_friend'];
            $command = self::$client->getCommand('NewFriend', array(
                'userID' => $friend
            ));
            $result = $command->execute();

            $this->assertNotNull($result->get('location'));

            return $result->get('location');
        }
        else {
            $this->markTestSkipped("To execute friend integration tests test_friend must be set in phpunit.xml");
        }
    }

    /**
     * @depends testNewFriend
     */
    public function testGetFriend($uri)
    {
        $command = self::$client->getCommand('GetFriend', array('uri' => $uri));
        $result = $command->execute();
        $this->assertNotNull($result->get('userID'));
    }

    public function testGetFriendFeed()
    {
        $command = self::$client->getCommand('GetFriendFeed');
        $result = $command->execute();

        $this->assertNotNull($result->get('items'));
    }

}

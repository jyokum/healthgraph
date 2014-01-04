<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group change
 */
class ChangeLogTest extends BaseIntegrationTest
{

    public function testUserIsLoaded()
    {
        $this->assertTrue(is_numeric(self::$client->getConfig('hg.userID')));
        $this->assertNotNull(self::$client->getConfig('hg.change_log'));
    }

    public function testGetChangeLog()
    {
        $result = self::$client->getCommand('GetChangeLog')->execute();
        $this->assertNotNull($result->get('fitness_activities'));
        $this->assertTrue(is_array($result->get('fitness_activities')));
    }

}

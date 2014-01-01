<?php

namespace HealthGraph\Tests\Integration;

/**
 * @group integration
 * @group records
 */
class RecordsTest extends BaseIntegrationTest
{

    public function testUserIsLoaded()
    {
        $this->assertTrue(is_numeric(self::$client->getConfig('hg.userID')));
        $this->assertNotNull(self::$client->getConfig('hg.records'));
    }

    public function testGetRecords()
    {
        $records = self::$client->getCommand('GetRecords')->execute()->getAll();
        $this->assertGreaterThanOrEqual(1, count($records));
    }

}

<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group nutrition
 */
class NutritionSetTest extends BaseTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'NutritionSet';
    }

    public function testPrepareNewCommand()
    {
        $command = $this->client->getCommand('New' . $this->base, array(
            'timestamp' => 'Sat, 1 Jan 2011 00:00:00',
            'calories' => 100,
        ));
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

}

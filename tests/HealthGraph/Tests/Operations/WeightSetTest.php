<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group weight
 */
class WeightSetTest extends BaseTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'WeightSet';
    }

    public function testPrepareNewCommand()
    {
        $command = $this->client->getCommand('New' . $this->base, array(
            'timestamp' => 'Sat, 1 Jan 2011 00:00:00',
            'weight' => 75,
        ));
        $command->prepare();
        $this->assertTrue($command->isPrepared());

        $op = $this->readAttribute($command, 'operation');
        $this->assertTrue($op->hasParam('timestamp'));
    }

}

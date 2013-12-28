<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group fitness
 */
class FitnessActivityTest extends BaseTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'FitnessActivity';
    }

    public function testPrepareNewCommand()
    {
        $command = $this->client->getCommand('New' . $this->base, array(
            'type' => 'Running',
            'start_time' => 'Sat, 1 Jan 2011 00:00:00',
            'duration' => '300'
        ));
        $command->prepare();
        $this->assertTrue($command->isPrepared());

        $op = $this->readAttribute($command, 'operation');
        $this->assertTrue($op->hasParam('type'));
        $this->assertTrue($op->hasParam('start_time'));
        $this->assertTrue($op->hasParam('duration'));
    }

}

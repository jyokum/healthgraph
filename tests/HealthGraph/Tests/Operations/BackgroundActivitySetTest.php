<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group background
 */
class BackgroundActivitySetTest extends BaseTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'BackgroundActivitySet';
    }

    public function testPrepareNewCommand()
    {
        $command = $this->client->getCommand('New' . $this->base, array(
            'timestamp' => 'Sat, 1 Jan 2011 00:00:00'
        ));
        $command->prepare();
        $this->assertTrue($command->isPrepared());

        $op = $this->readAttribute($command, 'operation');
        $this->assertTrue($op->hasParam('timestamp'));
        $this->assertTrue($op->hasParam('calories_burned'));
    }

}

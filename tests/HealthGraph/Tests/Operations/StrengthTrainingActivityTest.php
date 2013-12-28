<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group strength
 */
class StrengthTrainingActivityTest extends BaseTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'StrengthTrainingActivity';
    }

    public function testPrepareNewCommand()
    {
        $command = $this->client->getCommand('New' . $this->base, array(
            'exercises' => array(
                array(
                    'primary_muscle_group' => 'Arms',
                    'sets' => array(array(
                        'repetitions' => 10,
                        'weight' => 15
                    ))
                )
            )
        ));
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

}

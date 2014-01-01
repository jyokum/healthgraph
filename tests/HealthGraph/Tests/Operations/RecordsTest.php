<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group records
 */
class RecordsTest extends \Guzzle\Tests\GuzzleTestCase
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = "Records";
    }

    public function testOperationsExist()
    {
        $desc = $this->client->getDescription();
        $this->assertTrue($desc->hasOperation('Get' . $this->base), 'Get' . $this->base . ' operation is missing');
    }

    public function testModelsExist()
    {
        $desc = $this->client->getDescription();
        $this->assertTrue($desc->hasModel($this->base), $this->base . ' model is missing');
    }

    public function testGetCommandIsValid()
    {
        $object = $this->client->getCommand('GetRecords');
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $object);
    }

    public function testPrepareGetCommand()
    {
        $command = $this->client->getCommand('GetRecords');
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

}

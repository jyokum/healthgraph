<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group change
 */
class ChangeLogTest extends \Guzzle\Tests\GuzzleTestCase
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = "ChangeLog";
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
        $object = $this->client->getCommand('Get' . $this->base);
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $object);
    }

    public function testGetResponseIsExpected()
    {
        $command = $this->client->getCommand('Get' . $this->base);
        $op = $this->readAttribute($command, 'operation');
        $this->assertEquals($this->base, $op->getResponseClass());
    }

    public function testPrepareGetCommand()
    {
        $command = $this->client->getCommand('Get' . $this->base);
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

}

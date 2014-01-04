<?php

namespace HealthGraph\Tests\Operations;

/**
 * @group team
 */
class TeamTest extends \Guzzle\Tests\GuzzleTestCase
{

    protected $client;
    protected $base;

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'Friend';
    }

    public function testOperationsExist()
    {
        $desc = $this->client->getDescription();
        $this->assertTrue($desc->hasOperation('Get' . $this->base), 'Get' . $this->base . ' operation is missing');
        $this->assertTrue($desc->hasOperation('New' . $this->base), 'New' . $this->base . ' operation is missing');
    }

    public function testModelsExist()
    {
        $desc = $this->client->getDescription();
        $this->assertTrue($desc->hasModel($this->base), $this->base . ' model is missing');
    }

    public function testGetCommandIsValid()
    {
        $command = $this->client->getCommand('Get' . $this->base);
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $command);
    }

    public function testGetResponseIsExpected()
    {
        $command = $this->client->getCommand('Get' . $this->base);
        $op = $this->readAttribute($command, 'operation');
        $this->assertEquals($this->base, $op->getResponseClass());
    }

    public function testPrepareGetCommand()
    {
        $command = $this->client->getCommand('Get' . $this->base, array('uri' => '/foo'));
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    /**
     * @expectedException Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage [uri] is a required string
     */
    public function testPrepareGetCommandRequired()
    {
        $command = $this->client->getCommand('Get' . $this->base);
        $command->prepare();
        $this->assertTrue($command->isPrepared());
    }

    public function testNewCommandIsValid()
    {
        $command = $this->client->getCommand('New' . $this->base);
        $this->assertInstanceOf('\Guzzle\Service\Command\OperationCommand', $command);
    }

    public function testNewResponseIsExpected()
    {
        $command = $this->client->getCommand('New' . $this->base);
        $op = $this->readAttribute($command, 'operation');
        $this->assertEquals('GenericOutput', $op->getResponseClass());
    }

    public function testPrepareNewCommand()
    {
        $command = $this->client->getCommand('New' . $this->base, array(
            'userID' => '1000'
        ));
        $command->prepare();
        $this->assertTrue($command->isPrepared());

        $op = $this->readAttribute($command, 'operation');
        $this->assertTrue($op->hasParam('userID'));
    }

}

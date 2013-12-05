<?php

namespace HealthGraph\Response;

use Guzzle\Service\Command\ResponseClassInterface;
use Guzzle\Service\Command\OperationCommand;

class FitnessActivitiesFeed implements ResponseClassInterface
{
    protected $name;

    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse();
//        $xml = $command->xml();

        return new self();
    }

    public function __construct($name)
    {
        $this->name = $name;
    }
}
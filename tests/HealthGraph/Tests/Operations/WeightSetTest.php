<?php

namespace HealthGraph\Tests\Operations;

/**
 * @requires function skip_for_now
 * @TODO build supporting operations
 */
class WeightSetTest extends BaseTest
{

    protected function setUp()
    {
        $this->client = $this->getServiceBuilder()->get('client');
        $this->base = 'WeightSet';
    }

}

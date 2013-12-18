<?php

namespace HealthGraph\Tests\Common\Iterator;

use HealthGraph\Common\Iterator\HealthGraphIteratorFactory;
use Guzzle\Tests\Service\Mock\Command\MockCommand;

class HealthGraphIteratorFactoryTest extends \Guzzle\Tests\GuzzleTestCase
{

    /**
     * @covers HealthGraph\Common\Iterator\HealthGraphIteratorFactory
     */
    public function testInstantiateFactory()
    {
        $factory = new HealthGraphIteratorFactory();
        $this->assertInstanceOf('\Guzzle\Service\Resource\AbstractResourceIteratorFactory', $factory);
        $this->assertObjectHasAttribute('namespaces', $factory);
        $this->assertAttributeEquals(array(), 'namespaces', $factory);
        $this->assertObjectHasAttribute('inflector', $factory);
        $inflector = $this->readAttribute($factory, 'inflector');
        $this->assertInstanceOf('\Guzzle\Inflection\MemoizingInflector', $inflector);
    }

    /**
     * @covers HealthGraph\Common\Iterator\HealthGraphIteratorFactory
     */
    public function testInstantiateFactoryWithNamespaces()
    {
        $factory = new HealthGraphIteratorFactory(array('Foo', 'Bar'));
        $this->assertInstanceOf('\Guzzle\Service\Resource\AbstractResourceIteratorFactory', $factory);
        $this->assertAttributeEquals(array('Foo', 'Bar'), 'namespaces', $factory);
    }

    /**
     * @covers HealthGraph\Common\Iterator\HealthGraphIteratorFactory
     * @expectedException Exception
     * @expectedExceptionMessage must implement interface Guzzle\Inflection\InflectorInterface
     */
    public function testInstantiateFactoryWithInvalidInflector()
    {
        $inflector = new \stdClass();
        $factory = new HealthGraphIteratorFactory(array(), $inflector);
        $this->assertInstanceOf('\Guzzle\Service\Resource\AbstractResourceIteratorFactory', $factory);
    }

    /**
     * @covers HealthGraph\Common\Iterator\HealthGraphIteratorFactory::registerNamespace
     */
    public function testRegisterNamespace()
    {
        $factory = new HealthGraphIteratorFactory(array('Foo', 'Bar'));
        $factory->registerNamespace('Baz');
        $this->assertAttributeEquals(array('Baz', 'Foo', 'Bar'), 'namespaces', $factory);
    }

    /**
     * @covers HealthGraph\Common\Iterator\HealthGraphIteratorFactory::getClassName
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Iterator was not found for mock_command
     */
    public function testIteratorClassNotFound()
    {
        $factory = new HealthGraphIteratorFactory();
        $command = new MockCommand();
        $factory->build($command);
    }

}

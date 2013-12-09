<?php

namespace HealthGraph\Common\Iterator;

use Guzzle\Inflection\InflectorInterface;
use Guzzle\Inflection\Inflector;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Resource\AbstractResourceIteratorFactory;

/**
 * Factory for creating {@see ResourceIteratorInterface} objects using a convention of storing iterator classes under a
 * root namespace using the name of a {@see CommandInterface} object as a convention for determining the name of an
 * iterator class. The command name is converted to CamelCase and Iterator is appended (e.g. abc_foo => AbcFoo).
 */
class HealthGraphIteratorFactory extends AbstractResourceIteratorFactory
{

    /** @var array List of namespaces used to look for classes */
    protected $namespaces;

    /** @var InflectorInterface Inflector used to determine class names */
    protected $inflector;

    /**
     * @param string|array       $namespaces List of namespaces for iterator objects
     * @param InflectorInterface $inflector  Inflector used to resolve class names
     */
    public function __construct($namespaces = array(), InflectorInterface $inflector = null)
    {
        $this->namespaces = (array) $namespaces;
        $this->inflector = $inflector ? : Inflector::getDefault();
    }

    /**
     * Registers a namespace to check for Iterators
     *
     * @param string $namespace Namespace which contains Iterator classes
     *
     * @return self
     */
    public function registerNamespace($namespace)
    {
        array_unshift($this->namespaces, $namespace);

        return $this;
    }

    protected function getClassName(CommandInterface $command)
    {
        $iteratorName = $this->inflector->camel($command->getName()) . 'Iterator';
        $iteratorSpecified = $command->getOperation()->getData('iteratorClass');

        // @TODO fix this near duplication
        if (!is_null($iteratorSpecified)) {
            foreach ($this->namespaces as $namespace) {
                $potentialClassName = $namespace . '\\' . $iteratorSpecified;
                if (class_exists($potentialClassName)) {
                    return $potentialClassName;
                }
            }
        }

        foreach ($this->namespaces as $namespace) {
            $potentialClassName = $namespace . '\\' . $iteratorName;
            if (class_exists($potentialClassName)) {
                return $potentialClassName;
            }
        }

        return false;
    }

}

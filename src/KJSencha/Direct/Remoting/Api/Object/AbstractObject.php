<?php

namespace KJSencha\Direct\Remoting\Api\Object;

use Serializable;

abstract class AbstractObject implements Serializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $objectName;

    /**
     * @var mixed[]
     */
    private $children = array();

    /**
     * @param AbstractObject $objectName
     */
    public function __construct($objectName)
    {
        $this->setName($objectName);
        $this->setObjectName($objectName);
    }

    /**
     * Set the name of this object
     *
     * @param string $name Objectname
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of this object
     *
     * @param string $name Objectname
     */
    public function setObjectName($objectName)
    {
        $this->objectName = (string) $objectName;
    }

    /**
     * @return string
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param AbstractObject $child
     */
    public function addChild(AbstractObject $child)
    {
        $this->children[] = $child;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'name'       => $this->getName(),
            'objectName' => $this->getObjectName(),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
            'name'       => $this->getName(),
            'objectName' => $this->getObjectName(),
            'children'   => $this->getChildren(),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Incorrect unserialized data');
        }

        if (isset($data['name'])) {
            $this->setName($data['name']);
        }

        if (isset($data['objectName'])) {
            $this->setObjectName($data['objectName']);
        }

        if (isset($data['children'])) {
            $this->children = $data['children'];
        }
    }

    /**
     * Retrieve the array options as required by
     * http://www.sencha.com/products/extjs/extdirect/
     *
     * @return array
     */
    abstract public function toApiArray();
}

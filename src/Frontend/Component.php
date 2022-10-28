<?php

namespace KJSencha\Frontend;

/**
 * Component object
 *
 * Simple object representation of a extjs object
 */
class Component extends Base
{
    protected $className;

    /**
     * Create a Component class
     *
     * @param string $name
     * @param array  $attributes
     */
    public function __construct($name = NULL, array $attributes = NULL)
    {
        parent::__construct($name, $attributes);

        // Attempt to fetch classname
        if (is_string($name)) {
            $this->setClassname($name);
        } elseif (isset($this['className'])) {
            $this->setClassname($this['className']);
            unset($this['className']);
        }
    }

    /**
     * Set the classname
     *
     * @param  string                   $className
     * @return self
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Retrieve the classname
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set the class which this class should extend
     *
     * @param string $extends
     */
    public function setExtend($extends)
    {
        $this->setProperty('extend', $extends);

        return $this;
    }

    /**
     * Create this class javascript side
     *
     * @return string Optional name of class that is created
     */
    public function create($name = NULL)
    {
        $className = $name ?: $this['extend'];
        $className = $className ?: $this->getClassName();

        $output = sprintf(
            "Ext.create('%s', %s);",
            $className,
            $this->toJson()
        );

        return new Expr($output);
    }

    /**
     * Retrieve the code to define this PHP class
     *
     * @return
     */
    public function define()
    {
        $output = sprintf(
            "Ext.define('%s', %s);",
            $this->className,
            $this->toJson()
        );

        return new Expr($output);
    }
}
<?php

namespace Ipunkt\Calendar\Ical\Property;

/**
 * Property list.
 */
class PropertyList
{
    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * Set a property.
     *
     * @param  Property $property
     * @return self
     */
    public function set(Property $property)
    {
        return $this->removeAll($property->getName())->add($property);
    }

    /**
     * Add a property.
     *
     * @param  Property $property
     * @return self
     */
    public function add(Property $property)
    {
        $name = $property->getName();
        $hash = spl_object_hash($property);

        if (!isset($this->properties[$name])) {
            $this->properties[$name] = array();
        }

        $this->properties[$name][$hash] = $property;

        return $this;
    }

    /**
     * Remove all properties of a specific name.
     *
     * @param  string $name
     * @return self
     */
    public function removeAll($name)
    {
        if (isset($this->properties[$name])) {
            unset($this->properties[$name]);
        }

        return $this;
    }

    /**
     * Remove a single property.
     *
     * @param  Property $property
     * @return self
     */
    public function remove(Property $property)
    {
        $name = $property->getName();
        $hash = spl_object_hash($property);

        if (isset($this->properties[$name])) {
            if (isset($this->properties[$name][$hash])) {
                unset($this->properties[$name][$hash]);

                if (count($this->properties[$name]) === 0) {
                    unset($this->properties[$name]);
                }
            }
        }

        return $this;
    }

    /**
     * Clears the list of all properties.
     *
     * @return self
     */
    public function clear()
    {
        $this->properties = array();

        return $this;
    }

    /**
     * Get a single property of a specific name.
     *
     * @param  string $name
     * @return Property
     */
    public function get($name)
    {
        if (isset($this->properties[$name])) {
            return reset($this->properties[$name]);
        }

        return null;
    }

    /**
     * Get all properties of a specific name.
     *
     * @param  string $name
     * @return array
     */
    public function getAll($name)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }

        return array();
    }
}
<?php

namespace Ipunkt\Calendar\Components;

use Ipunkt\Calendar\Calendar;

class BaseComponent
{
    protected $type = '';
    protected $properties = [];
    protected $components = [];

    public function addProperty($property)
    {
        $this->properties[] = $property;
    }

    public function addComponent(BaseComponent $component)
    {
        $this->components[] = $component;
    }

    /**
     * Magic method: returns string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    public function toString()
    {
        return $this->getStringValue();
    }

    public function getStringValue()
    {
        $properties = implode(Calendar::LINE_GLUE, $this->properties);
        $components = implode(Calendar::LINE_GLUE, $this->components);

        return implode(Calendar::LINE_GLUE, array(
            'BEGIN:' . $this->getType(),
            $properties,
            $components,
            'END:' . $this->getType()
        ));
    }

    public function getType()
    {
        return $this->type;
    }
}
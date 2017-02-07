<?php

namespace Ipunkt\Calendar\Property;

use Ipunkt\Calendar\Property\Value\Data\Types\Text;

class TextProperty extends BaseProperty
{
    /**
     * sets value
     *
     * @param string|Text $value
     * @return $this
     */
    public function setValue($value)
    {
        if (!$value instanceof Text) {
            $value = new Text($value);
        }

        return parent::setValue($value);
    }
}
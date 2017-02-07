<?php

namespace Ipunkt\Calendar\Ical\Property\Value;

class Method extends Text
{
    /**
     * Set method.
     *
     * @param  string $method
     * @return self
     */
    public function setMethod($method)
    {
        $this->text = (string)$method;

        return $this;
    }

    /**
     * Get method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->text;
    }
}
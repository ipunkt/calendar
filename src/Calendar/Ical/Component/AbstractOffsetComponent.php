<?php

namespace Ipunkt\Calendar\Ical\Component;

use Ipunkt\Calendar\Ical\Exception;
use Ipunkt\Calendar\Ical\Property\Property;
use Ipunkt\Calendar\Ical\Property\Value;

/**
 * Abstract component.
 */
abstract class AbstractOffsetComponent extends AbstractComponent
{
    /**
     * sets start
     *
     * @param Value\DateTime|string $dateTime
     * @throws Exception\RuntimeException
     */
    public function setStart($dateTime)
    {
        $property = $this->properties()->get('DTSTART');

        if (!$dateTime instanceof Value\DateTime) {
            $dateTime = Value\DateTime::fromString($dateTime);
        }

        if ($property === null) {
            $property = new Property('DTSTART');
            $property->setValue($dateTime);
            $this->properties()->add($property);
        } elseif ($property->getValue() instanceof Value\DateTime) {
            /** @var Value\DateTime $dateTimeValue */
            $dateTimeValue = $property->getValue();
            $dateTimeValue->setDateTime($dateTime, false);
        } else {
            throw new Exception\RuntimeException('Value type of DTSTART property is not DateTime');
        }
    }

    /**
     * returns start
     *
     * @return Value\DateTime
     */
    public function getStart()
    {
        return $this->properties()->get('DTSTART');
    }

    /**
     * returns offset from
     *
     * @return mixed
     */
    public function getOffsetFrom()
    {
        return $this->properties()->get('TZOFFSETFROM');
    }

    /**
     * returns offset to
     *
     * @return mixed
     */
    public function getOffsetTo()
    {
        return $this->properties()->get('TZOFFSETTO');
    }
}

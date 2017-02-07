<?php

namespace Ipunkt\Calendar\Ical\Component;

use Ipunkt\Calendar\Ical\Property;

/**
 * Calendar component.
 */
class Calendar extends AbstractCustomContainerComponent
{
    /**
     * events in calendar
     *
     * @var Event[]
     */
    public $events = array();
    /**
     * version value
     *
     * @var Property\Value\Version
     */
    protected $version;
    /**
     * product id value
     *
     * @var Property\Value\ProductId
     */
    protected $productId;
    /**
     * calendar scale
     *
     * @var Property\Value\CalendarScale
     */
    protected $calendarScale;
    /**
     * method value
     *
     * @var Property\Value\Method
     */
    protected $method;
    /**
     * timezones in calendar
     *
     * @var Timezone[]
     */
    protected $timezones = array();

    /**
     * getName(): defined by AbstractComponent.
     *
     * @see    AbstractComponent::getName()
     * @return string
     */
    public function getName()
    {
        return 'VCALENDAR';
    }

    /**
     * Get version property.
     *
     * @return Property\Value\Version
     */
    public function getVersion()
    {
        if ($this->version === null) {
            $this->version = new Property\Value\Version();
        }

        return $this->version;
    }

    /**
     * Set calendar version.
     *
     * @param  string|Property\Value\Version $version
     * @return self
     */
    public function setVersion($version)
    {
        if (!$version instanceof Property\Value\Version) {
            $version = new Property\Value\Version($version);
        }

        $this->version = $version;

        return $this;
    }

    /**
     * Get product ID.
     *
     * @return Property\Value\ProductId
     */
    public function getProductId()
    {
        if ($this->productId === null) {
            $this->productId = new Property\Value\ProductId(md5(time()));
        }

        return $this->productId;
    }

    /**
     * Set product ID.
     *
     * @param  Property\Value\ProductId $productId
     * @return self
     */
    public function setProductId($productId)
    {
        if (!$productId instanceof Property\Value\ProductId) {
            $productId = new Property\Value\ProductId($productId);
        }

        $this->productId = $productId;

        return $this;
    }

    /**
     * Get calendar scale.
     *
     * @return Property\Value\CalendarScale
     */
    public function getCalendarScale()
    {
        if ($this->calendarScale === null) {
            $this->calendarScale = new Property\Value\CalendarScale('GREGORIAN');
        }

        return $this->calendarScale;
    }

    /**
     * Set calendar scale.
     *
     * @param  Property\Value\CalendarScale|string|null $calendarScale
     * @return self
     */
    public function setCalendarScale($calendarScale = null)
    {
        if ($calendarScale !== null && !$calendarScale instanceof Property\Value\CalendarScale) {
            $calendarScale = new Property\Value\CalendarScale($calendarScale);
        }

        $this->calendarScale = $calendarScale;

        return $this;
    }

    /**
     * Get method.
     *
     * @return Property\Value\Method
     */
    public function getMethod()
    {
        if ($this->method === null) {
            $this->method = new Property\Value\Method('REQUEST');
        }

        return $this->method;
    }

    /**
     * Set method.
     *
     * @param  Property\Value\Method|string|null $method
     * @return self
     */
    public function setMethod($method = null)
    {
        if ($method !== null && !$method instanceof Property\Value\Method) {
            $method = new Property\Value\Method($method);
        }

        $this->method = $method;

        return $this;
    }

    /**
     * Add an event.
     *
     * @param  Event $event
     * @return string
     */
    public function addEvent(Event $event)
    {
        //$uid = $event->getUid()->getUid();
        $this->events[] = $event;
        //return $uid;
    }

    /**
     * Add a timezone.
     *
     * @param  Timezone $timezone
     * @return self
     */
    public function addTimezone(Timezone $timezone)
    {
        $this->timezones[] = $timezone;

        return $this;
    }

    /**
     * Get a timezone.
     *
     * @param  string $timezoneId
     * @return Timezone
     */
    public function getTimezone($timezoneId)
    {
        foreach ($this->timezones as $timezone) {
            if ($timezone->getPropertyValue('TZID') === $timezoneId) {
                return $timezone;
            }
        }

        return null;
    }

    /**
     * Remove a timezone.
     *
     * @param  string $timezoneId
     * @return self
     */
    public function removeTimezone($timezoneId)
    {
        foreach ($this->timezones as $key => $timezone) {
            if ($timezone->getPropertyValue('TZID') === $timezoneId) {
                unset($this->timezones[$key]);
                break;
            }
        }

        return $this;
    }
}
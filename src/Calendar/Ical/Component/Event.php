<?php

namespace Ipunkt\Calendar\Ical\Component;

/**
 * Event component.
 */
class Event extends AbstractComponent
{
    /**
     * getName(): defined by AbstractComponent.
     *
     * @see    AbstractComponent::getName()
     * @return string
     */
    public function getName()
    {
        return 'VEVENT';
    }
}

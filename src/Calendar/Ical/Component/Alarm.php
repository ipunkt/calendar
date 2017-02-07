<?php

namespace Ipunkt\Calendar\Ical\Component;

/**
 * Alarm component.
 */
class Alarm extends AbstractComponent
{
    /**
     * getName(): defined by AbstractComponent.
     *
     * @see    AbstractComponent::getName()
     * @return string
     */
    public function getName()
    {
        return 'VALARM';
    }
}

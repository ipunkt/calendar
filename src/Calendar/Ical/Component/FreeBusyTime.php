<?php

namespace Ipunkt\Calendar\Ical\Component;

/**
 * FreeBusy time component.
 */
class FreeBusyTime extends AbstractComponent
{
    /**
     * getName(): defined by AbstractComponent.
     *
     * @see    AbstractComponent::getName()
     * @return string
     */
    public function getName()
    {
        return 'VFREEBUSY';
    }
}

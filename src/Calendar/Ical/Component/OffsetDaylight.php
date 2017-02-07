<?php

namespace Ipunkt\Calendar\Ical\Component;

/**
 * Daylight offset component.
 */
class OffsetDaylight extends AbstractOffsetComponent
{
    /**
     * getName(): defined by AbstractComponent.
     *
     * @see    AbstractComponent::getName()
     * @return string
     */
    public function getName()
    {
        return 'DAYLIGHT';
    }
}

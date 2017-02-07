<?php

namespace Ipunkt\Calendar\Ical\Component;

/**
 * Standard offset component.
 */
class OffsetStandard extends AbstractOffsetComponent
{
    /**
     * getName(): defined by AbstractComponent.
     *
     * @see    AbstractComponent::getName()
     * @return string
     */
    public function getName()
    {
        return 'STANDARD';
    }
}

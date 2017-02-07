<?php

namespace Ipunkt\Calendar\Ical\Component;

/**
 * Abstract custom container component.
 */
abstract class AbstractCustomContainerComponent extends AbstractComponent
{
    /**
     * Experimental components.
     *
     * @var array
     */
    protected $experimentalComponents = [];

    /**
     * IANA components
     *
     * @var array
     */
    protected $ianaComponents = [];

    /**
     * Add an experimental component.
     *
     * @param  Experimental $component
     * @return self
     */
    public function addExperimentalComponent(Experimental $component)
    {
        $this->experimentalComponents[] = $component;
        return $this;
    }

    /**
     * Add an IANA component.
     *
     * @param  Iana $component
     * @return self
     */
    public function addIanaComponent(Iana $component)
    {
        $this->ianaComponents[] = $component;
        return $this;
    }
}

<?php

namespace spec;

use PHPSpec2\ObjectBehavior;

class TeamCityPhpspecExtension extends ObjectBehavior
{

    /**
     * @param \PHPSpec2\ServiceContainer $container
     */
    function it_should_add_event_listener_on_initialisation($container)
    {
        $container->extend('event_dispatcher.listeners', ANY_ARGUMENT)->shouldBeCalled();
        $this->initialize($container);
    }

}

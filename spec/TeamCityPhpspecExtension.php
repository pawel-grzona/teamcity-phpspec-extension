<?php
namespace spec;

use PHPSpec2\ObjectBehavior;

class TeamCityPhpspecExtension extends ObjectBehavior
{

    /**
     * @param \PHPSpec2\ServiceContainer $container
     */
    function it_adds_event_listener_on_initialization($container)
    {
        $container->extend('event_dispatcher.listeners', ANY_ARGUMENT)->shouldBeCalled();
        $this->initialize($container);
    }

}
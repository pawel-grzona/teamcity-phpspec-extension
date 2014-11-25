<?php
namespace spec\PhpSpec\TeamCity;

use PhpSpec\ObjectBehavior;

class ExtensionSpec extends ObjectBehavior
{

    /**
     * @param \PhpSpec\ServiceContainer $container
     */
    function it_adds_event_listener_on_initialization($container)
    {
        $container->set('event_dispatcher.listeners', \Prophecy\Argument::any())->shouldBeCalled();
        $this->load($container);
    }
}

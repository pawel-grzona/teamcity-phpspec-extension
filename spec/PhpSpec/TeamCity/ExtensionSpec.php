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
        $container->set('formatter.formatters.teamcity', \Prophecy\Argument::type('Closure'))->shouldBeCalled();
        $this->load($container);
    }
}

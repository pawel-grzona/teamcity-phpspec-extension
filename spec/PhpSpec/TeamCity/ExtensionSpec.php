<?php
namespace spec\PhpSpec\TeamCity;

use PhpSpec\ObjectBehavior,
    PhpSpec\ServiceContainer,
    Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    function it_registers_TeamCity_formatter_when_loaded(ServiceContainer $container)
    {
        $container->set('formatter.formatters.teamcity', Argument::type('Closure'))->shouldBeCalled();
        $this->load($container);
    }
}

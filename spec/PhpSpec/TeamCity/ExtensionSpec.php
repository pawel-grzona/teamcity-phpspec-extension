<?php
namespace spec\PhpSpec\TeamCity;

use PhpSpec\ObjectBehavior;

class ExtensionSpec extends ObjectBehavior
{

    /**
     * @param \PhpSpec\ServiceContainer $container
     */
    function it_registers_TeamCity_formatter_when_loaded($container)
    {
        $container->set('formatter.formatters.teamcity', \Prophecy\Argument::type('Closure'))->shouldBeCalled();
        $this->load($container);
    }
}

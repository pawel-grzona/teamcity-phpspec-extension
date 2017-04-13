<?php
namespace spec\PhpSpec\TeamCity;

use PhpSpec\ObjectBehavior;
use PhpSpec\ServiceContainer;
use Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    public function it_is_phpspec_extention()
    {
        $this->shouldHaveType('PhpSpec\Extension');
    }

    public function it_registers_TeamCity_formatter_when_loaded(ServiceContainer $container)
    {
        $container->define('formatter.formatters.teamcity', Argument::type('Closure'))->shouldBeCalled();
        $this->load($container, []);
    }
}

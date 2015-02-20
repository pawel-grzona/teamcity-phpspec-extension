<?php
namespace spec\PhpSpec\TeamCity;

use PhpSpec\ObjectBehavior,
    PhpSpec\Event\SpecificationEvent,
    PhpSpec\Event\ExampleEvent,
    PhpSpec\Formatter\Presenter\PresenterInterface,
    PhpSpec\Console\IO,
    PhpSpec\Listener\StatisticsCollector,
    PhpSpec\Loader\Node\SpecificationNode,
    PhpSpec\Loader\Node\ExampleNode;

class FormatterSpec extends ObjectBehavior
{
    function let(PresenterInterface $presenter, IO $io, StatisticsCollector $stats)
    {
        $this->beConstructedWith($presenter, $io, $stats);
    }

    function it_formats_specification_start(IO $io, SpecificationNode $node)
    {
        $node->getTitle()->willReturn('Specification');
        $io->write("##teamcity[testSuiteStarted name='Specification']\n")->shouldBeCalled();
        $this->beforeSpecification($this->specificationEvent($node));
    }

    function it_formats_specification_finish(IO $io, SpecificationNode $node)
    {
        $node->getTitle()->willReturn('Specification');
        $io->write("##teamcity[testSuiteFinished name='Specification']\n")->shouldBeCalled();
        $this->afterSpecification($this->specificationEvent($node));
    }

    function it_formats_example_start(IO $io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write("##teamcity[testStarted name='Example' captureStandardOutput='true']\n")->shouldBeCalled();
        $this->beforeExample($this->exampleEvent($node));
    }

    function it_formats_example_finish(IO $io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write("##teamcity[testFinished name='Example' duration='1.2']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::PASSED, 0.0012));
    }

    function it_formats_failed_example(IO $io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write("##teamcity[testFailed name='Example' details='Exception!']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::FAILED, 0, 'Exception!'));
    }

    function it_formats_broken_example_as_failed(IO $io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write("##teamcity[testFailed name='Example' details='Exception!']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::BROKEN, 0, 'Exception!'));
    }

    function it_does_not_format_finish_of_failed_example(IO $io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write(\Prophecy\Argument::any())->shouldBeCalled();
        $io->write("##teamcity[testFinished name='Example' duration='1.2']\n")->shouldNotBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::FAILED, 0.0012));
    }

    function it_formats_ignored_example(IO $io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write("##teamcity[testIgnored name='Example' message='Exception!']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::PENDING, 0, 'Exception!'));
    }

    function it_escapes_special_characters(IO $io, ExampleNode $node)
    {
        $node->getTitle()->willReturn("Example '\n\r|[]");
        $io->write("##teamcity[testIgnored name='Example |'|\n|\r|||[|]' message='Exception |'|\n|\r|||[|]!']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::PENDING, 0, "Exception '\n\r|[]!"));
    }

    function it_escapes_unicode_symbols(IO $io, ExampleNode $node)
    {
        $node->getTitle()->willReturn("Example 0x1234");
        $io->write("##teamcity[testIgnored name='Example |0x1234' message='Exception |0x4321!']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::PENDING, 0, 'Exception 0x4321!'));
    }

    // -- Private

    private function specificationEvent(SpecificationNode $node)
    {
        return new SpecificationEvent($node->getWrappedObject());
    }

    private function exampleEvent(ExampleNode $node, $result = ExampleEvent::PASSED, $time = 0, $exceptionMessage = '')
    {
        return new ExampleEvent($node->getWrappedObject(), $time, $result, new \Exception($exceptionMessage));
    }
}

<?php
namespace spec\PhpSpec\TeamCity;

use PhpSpec\ObjectBehavior;
use PhpSpec\Event\SpecificationEvent;
use PhpSpec\Event\ExampleEvent;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Console\ConsoleIO;
use PhpSpec\Listener\StatisticsCollector;
use PhpSpec\Loader\Node\SpecificationNode;
use PhpSpec\Loader\Node\ExampleNode;

class FormatterSpec extends ObjectBehavior
{
    public function let(Presenter $presenter, ConsoleIO $io, StatisticsCollector $stats)
    {
        $this->beConstructedWith($presenter, $io, $stats);
    }

    public function it_formats_specification_start($io, SpecificationNode $node)
    {
        $node->getTitle()->willReturn('Specification');
        $io->write("##teamcity[testSuiteStarted name='Specification']\n")->shouldBeCalled();
        $this->beforeSpecification($this->specificationEvent($node));
    }

    public function it_formats_specification_finish($io, SpecificationNode $node)
    {
        $node->getTitle()->willReturn('Specification');
        $io->write("##teamcity[testSuiteFinished name='Specification']\n")->shouldBeCalled();
        $this->afterSpecification($this->specificationEvent($node));
    }

    public function it_formats_example_start($io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write("##teamcity[testStarted name='Example' captureStandardOutput='true']\n")->shouldBeCalled();
        $this->beforeExample($this->exampleEvent($node));
    }

    public function it_formats_example_finish($io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write("##teamcity[testFinished name='Example' duration='1.2']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::PASSED, 0.0012));
    }

    public function it_formats_failed_example($io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write("##teamcity[testFailed name='Example' details='Exception!']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::FAILED, 0, 'Exception!'));
    }

    public function it_formats_broken_example_as_failed($io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write("##teamcity[testFailed name='Example' details='Exception!']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::BROKEN, 0, 'Exception!'));
    }

    public function it_does_not_format_finish_of_failed_example($io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write(\Prophecy\Argument::any())->shouldBeCalled();
        $io->write("##teamcity[testFinished name='Example' duration='1.2']\n")->shouldNotBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::FAILED, 0.0012));
    }

    public function it_formats_ignored_example($io, ExampleNode $node)
    {
        $node->getTitle()->willReturn('Example');
        $io->write("##teamcity[testIgnored name='Example' message='Exception!']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::PENDING, 0, 'Exception!'));
    }

    public function it_escapes_special_characters($io, ExampleNode $node)
    {
        $node->getTitle()->willReturn("Example '\n\r|[]");
        $io->write("##teamcity[testIgnored name='Example |'|\n|\r|||[|]' message='Exception |'|\n|\r|||[|]!']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent($node, ExampleEvent::PENDING, 0, "Exception '\n\r|[]!"));
    }

    public function it_escapes_unicode_symbols($io, ExampleNode $node)
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

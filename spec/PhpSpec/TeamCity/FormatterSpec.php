<?php
namespace spec\PhpSpec\TeamCity;

use PhpSpec\ObjectBehavior,
    PhpSpec\Event\SpecificationEvent,
    PhpSpec\Event\ExampleEvent,
    PhpSpec\Formatter\Presenter\PresenterInterface,
    PhpSpec\Console\IO,
    PhpSpec\Listener\StatisticsCollector;

class FormatterSpec extends ObjectBehavior
{
    function let(PresenterInterface $presenter, IO $io, StatisticsCollector $stats)
    {
        $this->beConstructedWith($presenter, $io, $stats);
    }

    function it_formats_specification_start($io)
    {
        $io->write("##teamcity[testSuiteStarted name='Specification']\n")->shouldBeCalled();
        $this->beforeSpecification($this->specificationEvent());
    }

    function it_formats_example_start($io)
    {
        $io->write("##teamcity[testStarted name='Example' captureStandardOutput='true']\n")->shouldBeCalled();
        $this->beforeExample($this->exampleEvent());
    }

    function it_formats_example_finish($io)
    {
        $io->write("##teamcity[testFinished name='Example' duration='1.2']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent(ExampleEvent::PASSED, 0.0012));
    }

    function it_formats_failed_example($io)
    {
        $io->write("##teamcity[testFailed name='Example' details='See full log for details']\n")->shouldBeCalledTimes(2);
        $io->write("##teamcity[testFinished name='Example' duration='0']\n")->shouldNotBeCalled();

        foreach (array(ExampleEvent::FAILED, ExampleEvent::BROKEN) as $result) {
            $this->afterExample($this->exampleEvent($result));
        }
    }

    function it_formats_ignored_example($io)
    {
        $io->write("##teamcity[testIgnored name='Example' message='Exception!']\n")->shouldBeCalledTimes(1);
        $io->write("##teamcity[testFinished name='Example' duration='0']\n")->shouldNotBeCalled();
        $this->afterExample($this->exampleEvent(ExampleEvent::PENDING, 0, new \Exception('Exception!')));
    }

    function it_formats_specification_finish($io)
    {
        $io->write("##teamcity[testSuiteFinished name='Specification']\n")->shouldBeCalled();
        $this->afterSpecification($this->specificationEvent());
    }

    // -- Private

    private function specificationEvent()
    {
        return new SpecificationEvent(\Mockery::mock('PhpSpec\Loader\Node\SpecificationNode')->shouldReceive('getTitle')->once()->andReturn('Specification')->getMock());
    }

    private function exampleEvent($result = ExampleEvent::PASSED, $time = 0, $exception = null)
    {
        return new ExampleEvent(\Mockery::mock('PhpSpec\Loader\Node\ExampleNode')->shouldReceive('getTitle')->once()->andReturn('Example')->getMock(), $time, $result, $exception);
    }
}

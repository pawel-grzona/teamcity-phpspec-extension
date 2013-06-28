<?php

namespace spec;

use PHPSpec2\ObjectBehavior,
    PHPSpec2\Event\SpecificationEvent,
    PHPSpec2\Event\ExampleEvent;

class TeamCityPhpspecListener extends ObjectBehavior
{

    /**
     * @param \PHPSpec2\Console\IO $io
     */
    function let($io)
    {
        $this->beConstructedWith($io);
    }

    function it_should_return_subscribed_events()
    {
        self::getSubscribedEvents()->shouldReturn(array(
            'beforeSpecification' => 'beforeSpecification',
            'beforeExample'       => 'beforeExample',
            'afterExample'        => 'afterExample',
            'afterSpecification'  => 'afterSpecification'
        ));
    }

    function it_should_announce_specification_start($io)
    {
        $io->write("##teamcity[testSuiteStarted name='Specification']\n")->shouldBeCalled();
        $this->beforeSpecification($this->specificationEvent());
    }

    function it_should_announce_example_start($io)
    {
        $io->write("##teamcity[testStarted name='Example' captureStandardOutput='true']\n")->shouldBeCalled();
        $this->beforeExample($this->exampleEvent());
    }

    function it_should_announce_example_finish($io)
    {
        $io->write("##teamcity[testFinished name='Example' duration='1.2']\n")->shouldBeCalled();
        $this->afterExample($this->exampleEvent(ExampleEvent::PASSED, 0.0012));
    }

    function it_should_announce_failed_example($io)
    {
        $io->getWrappedSubject()->shouldReceive('write')->with("##teamcity[testFailed name='Example' details='See full log for details']\n")->twice();
        $io->write("##teamcity[testFinished name='Example' duration='0']\n")->shouldNotBeCalled();
        foreach (array(ExampleEvent::FAILED, ExampleEvent::BROKEN) as $result) $this->afterExample($this->exampleEvent($result));
    }

    function it_should_announce_ignored_example($io)
    {
        $io->getWrappedSubject()->shouldReceive('write')->with("##teamcity[testIgnored name='Example' message='Exception!']\n")->once();
        $io->write("##teamcity[testFinished name='Example' duration='0']\n")->shouldNotBeCalled();
        $this->afterExample($this->exampleEvent(ExampleEvent::PENDING, 0, new \Exception('Exception!')));
    }

    function it_should_announce_specification_finish($io)
    {
        $io->write("##teamcity[testSuiteFinished name='Specification']\n")->shouldBeCalled();
        $this->afterSpecification($this->specificationEvent());
    }

    // -- Private

    private function specificationEvent()
    {
        return new SpecificationEvent(\Mockery::mock('PHPSpec2\Loader\Node\Specification')->shouldReceive('getTitle')->once()->andReturn('Specification')->getMock());
    }

    private function exampleEvent($result = ExampleEvent::PASSED, $time = 0, $exception = null)
    {
        return new ExampleEvent(\Mockery::mock('PHPSpec2\Loader\Node\Example')->shouldReceive('getTitle')->once()->andReturn('Example')->getMock(), $time, $result, $exception);
    }

}

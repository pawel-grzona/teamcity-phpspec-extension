<?php
namespace PhpSpec\TeamCity;

use PhpSpec\Event\SpecificationEvent,
    PhpSpec\Event\ExampleEvent,
    PhpSpec\Console\IO,
    PhpSpec\Formatter\BasicFormatter;

class Formatter extends BasicFormatter
{
    public function beforeSpecification(SpecificationEvent $event)
    {
        $this->started('Suite', $event->getSpecification()->getTitle());
    }

    public function beforeExample(ExampleEvent $event)
    {
        $this->started('', $event->getExample()->getTitle(), " captureStandardOutput='true'");
    }

    public function afterExample(ExampleEvent $event)
    {
        $result = $event->getResult();
        $name = $event->getExample()->getTitle();

        if (ExampleEvent::PASSED != $result) {
            ExampleEvent::PENDING == $result ? $this->ignored($name, $event->getException()->getMessage()) : $this->failed($name);
            return;
        }

        $duration = $event->getTime() * 1000;
        $this->finished('', $name, " duration='$duration'");
    }

    public function afterSpecification(SpecificationEvent $event)
    {
        $this->finished('Suite', $event->getSpecification()->getTitle());
    }

    // -- Private

    private function started($type, $name, $param = '')
    {
        $this->event($type, 'Started', $name, $param);
    }

    private function finished($type, $name, $param = '')
    {
        $this->event($type, 'Finished', $name, $param);
    }

    private function failed($name)
    {
        $this->event('', 'Failed', $name, " details='See full log for details'");
    }

    private function ignored($name, $message)
    {
        $this->event('', 'Ignored', $name, " message='$message'");
    }

    private function event($type, $action, $name, $param)
    {
        $this->getIO()->write("##teamcity[test$type$action name='$name'$param]\n");
    }

}
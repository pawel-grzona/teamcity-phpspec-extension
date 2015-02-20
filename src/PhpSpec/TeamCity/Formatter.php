<?php
namespace PhpSpec\TeamCity;

use PhpSpec\Event\SpecificationEvent,
    PhpSpec\Event\ExampleEvent,
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
        ExampleEvent::PASSED == $event->getResult() ? $this->afterPassedExample($event) : $this->afterNotPassedExample($event);
    }

    public function afterSpecification(SpecificationEvent $event)
    {
        $this->finished('Suite', $event->getSpecification()->getTitle());
    }

    // -- Private

    private function afterPassedExample(ExampleEvent $event)
    {
        $duration = $event->getTime() * 1000;
        $this->finished('', $this->title($event), " duration='$duration'");
    }

    private function afterNotPassedExample(ExampleEvent $event)
    {
        $name = $this->title($event);
        $message = $event->getException()->getMessage();
        ExampleEvent::PENDING == $event->getResult() ? $this->ignored($name, $message) : $this->failed($name, $message);
    }

    private function started($type, $name, $param = '')
    {
        $this->event($type, 'Started', $name, $param);
    }

    private function finished($type, $name, $param = '')
    {
        $this->event($type, 'Finished', $name, $param);
    }

    private function failed($name, $message)
    {
        $this->event('', 'Failed', $name, " details='{$this->escape($message)}'");
    }

    private function ignored($name, $message)
    {
        $this->event('', 'Ignored', $name, " message='{$this->escape($message)}'");
    }

    private function event($type, $action, $name, $param)
    {
        $this->getIO()->write("##teamcity[test$type$action name='{$this->escape($name)}'$param]\n");
    }

    private function title(ExampleEvent $event)
    {
        return $event->getExample()->getTitle();
    }

    private function escape($text)
    {
        return preg_replace("/(['\n\r|\[\]]|0x\d+)/", '|$1', $text);
    }
}

<?php
namespace PhpSpec\TeamCity;

use PhpSpec\ServiceContainer;
use PhpSpec\Extension as PhpSpecExtension;

class Extension implements PhpSpecExtension
{
    public function load(ServiceContainer $container, array $params)
    {
        $container->set('formatter.formatters.teamcity', function (ServiceContainer $container) {
            return new Formatter(
                $container->get('formatter.presenter'),
                $container->get('console.io'),
                $container->get('event_dispatcher.listeners.stats')
            );
        });
    }
}

<?php
namespace PhpSpec\TeamCity;

use PhpSpec\Extension\ExtensionInterface,
    PhpSpec\ServiceContainer;

class Extension implements ExtensionInterface
{
    public function load(ServiceContainer $container)
    {
        $container->set('formatter.formatters.teamcity', function(ServiceContainer $container) {
            return new Formatter($container->get('formatter.presenter'), $container->get('console.io'), $container->get('event_dispatcher.listeners.stats'));
        });
    }
}

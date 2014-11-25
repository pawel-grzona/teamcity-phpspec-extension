<?php
namespace PhpSpec\TeamCity;

use PhpSpec\Extension\ExtensionInterface,
    PhpSpec\ServiceContainer;

class Extension implements ExtensionInterface
{
    public function load(ServiceContainer $container)
    {
        $container->set('event_dispatcher.listeners', function($container) {
            return new Listener($container->get('io'));
        });
    }
}

<?php

use PHPSpec2\Extension\ExtensionInterface,
    PHPSpec2\ServiceContainer;

class TeamCityPhpspecExtension implements ExtensionInterface
{

    public function initialize(ServiceContainer $container)
    {
        $container->extend('event_dispatcher.listeners', function($container) {
                return new TeamCityPhpspecListener($container->get('io'));
            });
    }

}
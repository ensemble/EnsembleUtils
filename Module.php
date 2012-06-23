<?php

namespace SlmCmfUtils;

use Zend\ModuleManager\Feature;
use Zend\EventManager\Event;

class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfiguration()
    {
        return include __DIR__ . '/config/services.config.php';
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}

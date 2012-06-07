<?php

namespace SlmCmfUtils;

use Zend\ModuleManager\Feature;
use Zend\EventManager\Event;

use SlmCmfUtils\View\Helper\Url;

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
        return array(
            'factories' => array(
                'SlmCmfUtils\View\Helper\Url' => function ($sm) {
                    $helper = new Url;
                    $router = $sm->get('router');
                    $helper->setRouter($router);
                    
                    $event  = $sm->get('application')->getMvcEvent();
                    $match  = $event->getRouteMatch();
                    $helper->setRouteMatch($match);
                    
                    return $helper;
                }
            ),
        );
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}

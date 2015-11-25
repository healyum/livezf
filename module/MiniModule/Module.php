<?php
namespace MiniModule;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\View\Resolver\TemplateMapResolver;

class Module implements BootstrapListenerInterface, ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        // $e->getTarget() renvoit Zend\MVC\Application
        $application = $e->getTarget();
        $sm = $application->getServiceManager();
        $route = $sm->get('Router');
        $route = new TreeRouteStack();
        $route->addRoute('home', Literal::factory(array(
            'route' => '/',
            'defaults' => array(
                'controller' => 'index',
                'action' => 'index'
                )
            )
        ));

        $event = $application->getEventManager();
        $event->attach(MvcEvent::EVENT_DISPATCH_ERROR, function(MvcEvent $e) {
            error_log($e->getError());
            error_log($e->getControllerClass().' '.$e->getController());
        });

        /*
        $view = $sm->get('ViewManager');
        $resolv = new TemplateMapResolver(array(
                'error' => __DIR__.'/view/error.phtml',
                'layout/layout' => __DIR__.'/view/layout/layout.phtml',
            )
        );
        $view->getRenderer()->setResolver($resolv);
        */
	}
}
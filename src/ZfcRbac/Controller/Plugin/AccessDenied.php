<?php

namespace ZfcRbac\Controller\Plugin;

use RuntimeException;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class AccessDenied extends AbstractPlugin
{
    /**
     * @param  $permission
     * @param null|Closure|AssertionInterface $assert
     * @throws RuntimeException
     * @return bool
     */
    public function __invoke()
    {
        $controller = $this->getController();
        if (!$controller instanceof ServiceLocatorAwareInterface) {
            throw new RuntimeException('Controller must implement ServiceLocatorAwareInterface to use this plugin');
        }

        $app = $controller->getServiceLocator()->get('Application');
        $em = $app->getEventManager();
        $mvcEvent = $app->getMvcEvent();
        $mvcEvent->setError(\ZfcRbac\Service\Rbac::ERROR_CONTROLLER_UNAUTHORIZED)
            ->setParam('identity', $controller->getIdentity())
            ->setParam('route',$controller -> getEvent() -> getRouteMatch() -> getParam('route'))
            ->setParam('controller',$controller -> getEvent() -> getRouteMatch() -> getParam('controller'))
            ->setParam('action',$controller -> getEvent() -> getRouteMatch() -> getParam('action'));
        $em->trigger('dispatch.error', $mvcEvent);
        $response = $mvcEvent->getResult();
        return $response; 
    }
}
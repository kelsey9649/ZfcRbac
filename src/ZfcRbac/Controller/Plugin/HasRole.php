<?php

namespace ZfcRbac\Controller\Plugin;

use RuntimeException;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class HasRole extends AbstractPlugin
{
    /**
     * @param string $role
     * @throws RuntimeException
     * @return bool
     */
    public function __invoke($role)
    {
        $controller = $this->getController();
        if (!$controller instanceof ServiceLocatorAwareInterface) {
            throw new RuntimeException('Controller must implement ServiceLocatorAwareInterface to use this plugin');
        }
        $rbacService = $controller->getServiceLocator()->get('ZfcRbac\Service\Rbac');
        return $rbacService->hasRole($role);
    }
}
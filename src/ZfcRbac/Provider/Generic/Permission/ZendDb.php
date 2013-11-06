<?php

namespace ZfcRbac\Provider\Generic\Permission;

use DomainException;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use ZfcRbac\Provider\AbstractProvider;
use ZfcRbac\Provider\Event;

class ZendDb extends AbstractProvider
{
    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var DoctrineDbalOptions
     */
    protected $options;

    /**
     * @param Connection $connection
     * @param array $options
     */
    public function __construct(\Zend\Db\Adapter\Adapter $adapter, array $options)
    {
        $this->adapter = $adapter;
        $this->options    = new ZendDbOptions($options);
    }

    /**
     * Attach to the listeners.
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $events->attach(Event::EVENT_LOAD_PERMISSIONS, array($this, 'loadPermissions'));
    }

    /**
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        $events->detach($this);
    }

    /**
     * Load permissions into roles.
     *
     * @param Event $e
     */
    public function loadPermissions(Event $e)
    {
        $rbac    = $e->getRbac();
        $sql     = new Sql($this->adapter);
        $select  = $sql->select();
        $options = $this->options;
        
        $select->columns(array('permission'=>"{$options->getPermissionNameColumn()}"))
                ->from(array('p'=>$options->getPermissionTable()))
                ->join(
                    array('rp'=>$options->getRoleJoinTable()),
                    "p.{$options->getPermissionIdColumn()} = rp.{$options->getPermissionJoinColumn()}",
                    array(),
                    $select::JOIN_LEFT
                )->join(
                    array('r'=>$options->getRoleTable()),
                    "rp.{$options->getRoleJoinColumn()} = r.{$options->getRoleIdColumn()}",
                    array('role'=>"{$options->getRoleNameColumn()}"),
                    $select::JOIN_LEFT
                );
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        foreach($result as $row) {
            
            if ($rbac->hasRole($row['role'])) {
                //\Zend\Debug\Debug::dump($row['role']);
                $rbac->getRole($row['role'])->addPermission($row['permission']);
            }
        }
    }

    /**
     * Factory to create the provider.
     *
     * @static
     * @param ServiceLocatorInterface $sl
     * @param array                   $spec
     * @throws DomainException
     * @return DoctrineDbal
     */
    public static function factory(ServiceLocatorInterface $sl, array $spec)
    {
        $adapter = isset($spec['adapter']) ? $spec['adapter'] : null;
        if (!$adapter) {
            throw new DomainException('Missing required parameter: adapter');
        }

        $options = isset($spec['options']) ? (array) $spec['options'] : array();
        if (!is_string($adapter) || $sl->has($adapter)) {
            $adapter = $sl->get($adapter);
        } else {
            throw new DomainException('Failed to get DB adapter');
        }

        return new ZendDb($adapter, $options);
    }
}
<?php

namespace ZfcRbac\Provider\AdjacencyList\Role;

use DomainException;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
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
     * @var ZendDbOptions
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
        $events->attach(Event::EVENT_LOAD_ROLES, array($this, 'loadRoles'));
    }

    /**
     * @param EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        $events->detach($this);
    }

    /**
     * Load roles at RBAC creation.
     *
     * @param Event $e
     * @return array
     */
    public function loadRoles(Event $e)
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $options = $this->options;

        $select->columns(array('name'=>"{$options->getNameColumn()}"))
               ->from(array('role'=>$options->getTable()))
               ->join(
                    array('parent'=>$options->getTable()),
                    "role.{$options->getJoinColumn()} = parent.{$options->getIdColumn()}",
                    array('parent'=>"{$options->getNameColumn()}"),
                    $select::JOIN_LEFT
                );
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $roles = array();
        foreach($result as $row) {
            $parentName = isset($row['parent']) ? $row['parent'] : 0;
            unset($row['parent']);

            $roles[$parentName][] = $row['name'];
        }
        
        //\Zend\Debug\Debug::dump($roles);
        $this->recursiveRoles($e->getRbac(), $roles);
    }

    /**
     * Factory to create the provider.
     *
     * @static
     * @param ServiceLocatorInterface $sl
     * @param array                   $spec
     * @throws DomainException
     * @return ZendDb
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
            throw new DomainException('Failed to find Zend DB adapter');
        }

        return new ZendDb($adapter, $options);
    }
}
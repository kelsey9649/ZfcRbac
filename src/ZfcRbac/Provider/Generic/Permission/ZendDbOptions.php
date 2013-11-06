<?php

namespace ZfcRbac\Provider\Generic\Permission;

use Zend\Stdlib\AbstractOptions;

class ZendDbOptions extends AbstractOptions
{
    /**
     * The name of the table the permissions are stored in.
     *
     * @var string
     */
    protected $permissionTable = 'permission';

    /**
     * The name of the table the permissions are stored in.
     *
     * @var string
     */
    protected $roleTable = 'role';

    /**
     * The name of the table used to join roles to permissions.
     *
     * @var string
     */
    protected $roleJoinTable = 'role_permission';

    /**
     * The id column of the permission table.
     *
     * @var string
     */
    protected $permissionIdColumn = 'id';

    /**
     * The join table permission id column.
     *
     * @var string
     */
    protected $permissionJoinColumn = 'permission_id';

    /**
     * The id column of the role table.
     *
     * @var string
     */
    protected $roleIdColumn = 'id';

    /**
     * The join table role id column.
     *
     * @var string
     */
    protected $roleJoinColumn = 'role_id';

    /**
     * The name column of the permission table.
     *
     * @var string
     */
    protected $permissionNameColumn = 'name';

    /**
     * The name column of the role table.
     *
     * @var string
     */
    protected $roleNameColumn = 'name';

    /**
     * @param  string $permissionIdColumn
     * @return ZendDbOptions
     */
    public function setPermissionIdColumn($permissionIdColumn)
    {
        $this->permissionIdColumn = (string) $permissionIdColumn;
        return $this;
    }

    /**
     * @return string
     */
    public function getPermissionIdColumn()
    {
        return $this->permissionIdColumn;
    }

    /**
     * @param string $permissionJoinColumn
     * @return ZendDbOptions
     */
    public function setPermissionJoinColumn($permissionJoinColumn)
    {
        $this->permissionJoinColumn = (string) $permissionJoinColumn;
        return $this;
    }

    /**
     * @return string
     */
    public function getPermissionJoinColumn()
    {
        return $this->permissionJoinColumn;
    }

    /**
     * @param string $permissionNameColumn
     * @return ZendDbOptions
     */
    public function setPermissionNameColumn($permissionNameColumn)
    {
        $this->permissionNameColumn = (string) $permissionNameColumn;
        return $this;
    }

    /**
     * @return string
     */
    public function getPermissionNameColumn()
    {
        return $this->permissionNameColumn;
    }

    /**
     * @param string $permissionTable
     * @return ZendDbOptions
     */
    public function setPermissionTable($permissionTable)
    {
        $this->permissionTable = (string) $permissionTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getPermissionTable()
    {
        return $this->permissionTable;
    }

    /**
     * @param string $roleIdColumn
     * @return ZendDbOptions
     */
    public function setRoleIdColumn($roleIdColumn)
    {
        $this->roleIdColumn = (string) $roleIdColumn;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoleIdColumn()
    {
        return $this->roleIdColumn;
    }

    /**
     * @param string $roleJoinColumn
     * @return ZendDbOptions
     */
    public function setRoleJoinColumn($roleJoinColumn)
    {
        $this->roleJoinColumn = (string) $roleJoinColumn;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoleJoinColumn()
    {
        return $this->roleJoinColumn;
    }

    /**
     * @param string $roleJoinTable
     * @return ZendDbOptions
     */
    public function setRoleJoinTable($roleJoinTable)
    {
        $this->roleJoinTable = (string) $roleJoinTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoleJoinTable()
    {
        return $this->roleJoinTable;
    }

    /**
     * @param string $roleNameColumn
     * @return ZendDbOptions
     */
    public function setRoleNameColumn($roleNameColumn)
    {
        $this->roleNameColumn = (string) $roleNameColumn;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoleNameColumn()
    {
        return $this->roleNameColumn;
    }

    /**
     * @param string $roleTable
     * @return ZendDbOptions
     */
    public function setRoleTable($roleTable)
    {
        $this->roleTable = (string) $roleTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoleTable()
    {
        return $this->roleTable;
    }
}
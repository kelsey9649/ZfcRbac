<?php

namespace ZfcRbac\Identity;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use InvalidArgumentException;

class CASIdentity implements IdentityInterface
{
    /**
     * Array of roles.
     *
     * @var array
     */
    protected $roles;
    
    /**
     * User name string
     * 
     * @var string
     */
    protected $name;

    /**
     * @param $roles
     */
    public function __construct($name='Guest')
    {       
        $this->name = $name;
    }

    /**
     * Retrieve the current identity's roles
     * @return array
     */
    public function getRoles($adapter = null)
    {
        if($adapter)
        {
            $sql = new Sql($adapter);
            $select = $sql->select();
             
            $select->from(array('r'=>"rbac_role"))
                   ->join(array('ru'=>"rbac_role_user"),"r.role_id = ru.role_id",array())
                   ->join(array('u'=>"rbac_user"),"ru.user_id = u.user_id",array())
                   ->where(array('u.user_login'=>$this->name));
             
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
             
            $this -> roles = array();
             
            foreach($result as $row){
               $this -> roles[] = $row['role_name'];    
            }
            
        }else if(!$this->roles){
            throw \Exception('No roles were found for the identity.');    
        }
        
        return $this->roles;
    }
    
    /*
     * Retrieve the current identity's name
     */
    public function getName()
    {
        return $this->name;
    }
}
<?php
require_once('app/vendor/autoload.php');
class User extends Controller{
    public $admin_role;
    public $I_ADMIN;
    public $auth;
    public function __construct()
    {
        $conString = 'mysql:host=' . SQL_HOST . ';dbname=' . SQL_DB;
        $this->dbConn = new PDO($conString, SQL_USER, SQL_PASS);
        $this->admin_role = \Delight\Auth\Role::ADMIN;
        $this->auth = new \Delight\Auth\Auth($this->dbConn);
        $this->I_ADMIN = $this->auth->hasAnyRole($this->admin_role);
        
    }
}


?>
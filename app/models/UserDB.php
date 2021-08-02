<?php
class UserDB extends Model 
{
    function __construct()
    {
        Parent::__construct();
    }

    public function registerUser($ins){
    
        $id = $this->insert($ins,'dt_users');
        return $id;

    }
    public function userDetails($auth_id){
        $query = 'SELECT u.*, c.name AS cso_name FROM wash_users u LEFT JOIN cso c ON u.cso_id = c.id WHERE u.auth_id = '.$auth_id;
        $result = $this->query($query);
        $data = mysqli_fetch_assoc($result);
        return $data;
    }
}


?>
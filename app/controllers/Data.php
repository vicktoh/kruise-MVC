<?php
require_once('app/vendor/autoload.php');

class Data extends Controller{
    function __construct()
    {
        $conString = 'mysql:host=' . SQL_HOST . ';dbname=' . SQL_DB;
        $this->dbConn = new PDO($conString, SQL_USER, SQL_PASS);
    }
    public function fetch(){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $userId = $auth->getUserId();
        //TODO check for API key
        if(!$userId){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "You are not loggedIn"
            ));
        }
        $params = $this->input_post(true);
        $db = $this->load_model("DataDb");
        $data = $db->fetch_data($params["options"], $params["page"]);
        $this->sendJSON(Array(
            "status" => OKAY_RESPONSE,
            "data" => $data
        ));
    }

    public function put_data(){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $userId = $auth->getUserId();
        //TODO check for API key
        if(!$userId){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "You are not loggedIn"
            ));
        }
        // $params = $this->input_post(true);
        var_dump($_FILES);
        die("");
        $db = $this->load_model("DataDb");
        $this->uploadfile("file", DOCUMENTS);
        $data = $db->putdata($params);
        $this->sendJSON(Array(
            "status" => OKAY_RESPONSE,
            "data" => $data
        ));
    }
    public function update_data($id){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $userId = $auth->getUserId();
        //TODO check for API key
        if(!$userId){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "You are not loggedIn"
            ));
        }
        $params = $this->input_post(true);
        $db = $this->load_model("DataDb");
        $data = $db->updatedata($id, $params);
        $this->sendJSON(Array(
            "status" => OKAY_RESPONSE,
            "data" => $data
        ));
    }

    public function fetch_places(){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $userId = $auth->getUserId();
        //TODO check for API key
        if(!$userId){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "You are not loggedIn"
            ));
        }
        $params = $this->input_post(true);
        $db = $this->load_model("DataDb");
        $data = $db->fetch_places($params["page"]);
        $this->sendJSON(Array(
            "status" => OKAY_RESPONSE,
            "data" => $data
        ));
    }
    

    public function put_place(){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $userId = $auth->getUserId();
        //TODO check for API key
        if(!$userId){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "You are not loggedIn"
            ));
        }
        $params = $this->input_post(true);
        $db = $this->load_model("DataDb");
        $id = $db->put_place($params);
        $this->sendJSON(Array(
            "status" => OKAY_RESPONSE,
            "data" => Array(
                "id" => $id
            )
        ));
    }

    public function update_place($id){
        if(!$id){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "id parameter not passed but required"
            ));
            return;
        }
        //TODO check for API key
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $userId = $auth->getUserId();
        if(!$userId){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "You are not loggedIn"
            ));
        }
        $params = $this->input_post(true);
        $db = $this->load_model("DataDb");
        $id = $db->updateplace($id, $params);
        $this->sendJSON(Array(
            "status" => OKAY_RESPONSE,
            "data" => Array(
                "id" => $id
            )
        ));
    }

    public function fetch_categories(){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $userId = $auth->getUserId();
        //TODO check for API key
        if(!$userId){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "You are not loggedIn"
            ));
        }
        $db = $this->load_model("DataDb");
        $params = $this->input_post(true);
        $data = $db->fetch_categories($params["page"]);
        $this->sendJSON(Array(
            "status" => OKAY_RESPONSE,
            "data" => $data
        ));
    }
    public function put_category(){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $userId = $auth->getUserId();
        //TODO check for API key
        if(!$userId){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "You are not loggedIn"
            ));
        }
        $params = $this->input_post(true);
        $db = $this->load_model("DataDb");
        $id = $db->put_category($params);
        $this->sendJSON(Array(
            "status" => OKAY_RESPONSE,
            "data" => Array(
                "id" => $id
            )
        ));
    }

    public function update_category($id){
        if(!$id){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "id parameter not passed but required"
            ));
            return;
        }
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $userId = $auth->getUserId();
        //TODO check for API key
        if(!$userId){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "You are not loggedIn"
            ));
        }
        $params = $this->input_post(true);
        $db = $this->load_model("DataDb");
        $id = $db->update_category($id, $params);
        $this->sendJSON(Array(
            "status" => OKAY_RESPONSE,
            "data" => Array(
                "id" => $id
            )
        ));

    }
    public function delete($id){
        if(!$id){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "id parameter not passed but required"
            ));
            return;
        }
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $userId = $auth->getUserId();
        //TODO check for API key
        if(!$userId){
            $this->sendJSON(Array(
                "status" => DANGER_RESPONSE,
                "message"=> "You are not logged in"
            ));
        }
        $params = $this->input_post(true);
        $db = $this->load_model("DataDb");
        $db->delete_entity($id, $params["table"]);
        $this->sendJSON(Array(
            "status" => OKAY_RESPONSE,
            "data" => Array(
                "id" => $id
            )
        ));
    }


}
?>
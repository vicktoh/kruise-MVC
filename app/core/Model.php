<?php
// Model an abstract class that exports basic database functionalities like connecting, reading
//updating e.t.c the database
class Model{
    protected $conn = null;
    protected $result = null;
    public $error = null;
    public $errorNo = null;
    public $absPath = "http://localhost/budeshi-2.0/webroot/";


    function __construct(){
        $this->conn = new mysqli(SQL_HOST,SQL_USER,SQL_PASS,SQL_DB);
        if(mysqli_error($this->conn)){
        echo "Failed to connect to the database check Configuration settings".msyqli_error();
        die();

        }
    }
    protected function insert_id(){
        return $this->conn->insert_id;
    }

    function query($querystring){
        $this->result = $this->conn->query($querystring);
        if(!$this->result){
            die("SQL error ".$this->conn->error);
           
        }
        else{
           return $this->result;
        }

    }
    public function escape($value){
        return $this->conn->real_escape_string($value);
    }

    public function update($id, $fieldSet, $table, $where = "id"){
        $fieldString = "";
        if(!empty($fieldSet)){
            foreach($fieldSet as $name=> $value){
                if(is_string($value))
                $fieldString .= $name."= '".$this->conn->real_escape_string($value)."', ";
                else
                $fieldString .= $name."=".$value.", ";

            }
            $fieldString = rtrim($fieldString,' ,');
        }
        else{
            echo "Error Empty FieldSet passed to update function..";
            print_r($fieldSet);
            die();
        }
        $query = "UPDATE ".$table." SET ".$fieldString." WHERE ".$where." = '".$id."'";
        $result = $this->query($query);
        
        return $result;
    }

    public function read($id, $table = "institution"){
        $query = "SELECT * FROM ".$this->conn->real_escape_string($table)." WHERE id = ".$id;
        $result = $this->query($query);
        return $result;
    }

    public function delete($id, $table){
        $query = "DELETE FROM ".$this->conn->real_escape_string($table)." WHERE id = ".$this->conn->real_escape_string($id)." LIMIT 1;";
        $result = $this->query($query);
        if(!$result){
            echo $this->error;
            die();
        }
        return $result;
    }
    //create function requires fieldset parmeter to be assoc array of tablecolumn=>value set
    public function insert($fieldset, $table){
        $fieldString = [];
        $valueString = [];
        if(!empty($fieldset)){
            foreach($fieldset as $name=> $value){
                $fieldString[] = $name;
                if(is_string($value)){
                $valueString[] = "'".$this->conn->real_escape_string($value)."'";
                }
                else{
                    $valueString[] = $value;
                }
            }
        }
        $fields = "(".implode(",",$fieldString).")";
        $values = "(".implode(",", $valueString).")";

        $query = "INSERT INTO ".$table.$fields." VALUES ".$values;
        $result = $this->query($query);
        if(!$result){
            echo $this->error;
            die();
        }
        else{
            $result = $this->conn->insert_id;
        }
        return $result;

    }
    public function queryToJson($query, $prefix= ""){
        $output = array();
        $result = $this->query($query);
        if(!$result){
            die($this->error);
        }
        if(mysqli_num_rows($result) <= 0){
            return "empty";
        }
        else{
            while($row = mysqli_fetch_assoc($result)){
                foreach($row as $name=>$value){
                    $output[$prefix.$name] = $value;
                }
            }
        
            $output["ajaxstatus"] = "success";
            $output["message"] = "Fetched successfully";
            $output = json_encode($output);
            return $output; 
        }
    }
   
    protected function trimText($text, $max = 100, $pgrh = 1)
    {

        $textToReturn = '';
        $len = strlen($text);
        if (strlen($text) > $max) {
            for ($i = 0; $i < $pgrh; $i++)
                {
                if ($pos = strpos($text, '\n'))
                    {
                    $textToReturn .= substr($text, 0, $pos);
                    $text = substr($text, $pos + 1, $len);
                }
                else {
                    $pos = strrpos($text, ' ');
                    $textToReturn .= substr($text, 0, $max) . "...";
                }
            }
        }
        else {
            $textToReturn = $text;
        }
        return $textToReturn;
    }
    protected function load($name, $type = "helper"){
        $to_return;
        switch($type){
            case "helper":
                $name = ucfirst(strtolower($name));
                $helper = HELPERS.$name.".php";
                if(file_exists($helper)){
                    require_once($helper);
                    $to_return = new $name;

                }
                else{
                    echo "Cannot find helper at ".$helper;
                    die();
                }
            break;
            case "library":
                $name = ucfirst(strtolower($name));
                $library = LIBRARIES.$name.".php";
                if(file_exists($library)){
                    require_once($library);
                    $to_return = new $name;
                }
            break;
            default:
                echo "improper use of function";
                die;
            break;
        }
        return $to_return;
    }
    /** Returns a the column of an sql result object as an array
     * @params $result_obj mysqli result object
     * @params $column_name name of the column
     */
    public function result_columns($result_obj, $column_name){
        $output = array();
        while($row = $result_obj->fetch_assoc()){
            $output [] = $row[$column_name];
        }
        return $output;
    }
    public function upsert($insert_fields,$upsert_fields,$table){
        $fieldString = [];
        $valueString = [];
        if(!empty($insert_fields)){
            foreach($insert_fields as $name=> $value){
                $fieldString[] = $name;
                if(is_string($value)){
                $valueString[] = "'".$this->conn->real_escape_string($value)."'";
                }
                else{
                    $valueString[] = $value;
                }
            }
        }
        $fields = "(".implode(",",$fieldString).")";
        $values = "(".implode(",", $valueString).")";

        $insert_query = "INSERT INTO ".$table.$fields." VALUES ".$values;
        $fieldString = "";
        if(!empty($upsert_fields)){
            foreach($upsert_fields as $name=> $value){
                if(is_string($value))
                $fieldString .= $name."= '".$this->conn->real_escape_string($value)."', ";
                else
                $fieldString .= $name."=".$value.", ";

            }
            $fieldString = rtrim($fieldString,' ,');
        }
        else{
            echo "Error Empty FieldSet passed to update function..";
            print_r($fieldSet);
            die();
        }
        $update_query = " UPDATE ".$fieldString;
        $query = $insert_query." ON DUPLICATE KEY ".$update_query;
        $this->query($query);
        return true;
    }
    protected function load_helper($name)
    {
        if (is_array($name)) {
            foreach ($name as $nm) {
                $helpername = HELPERS . $nm . ".php";
                if (file_exists($helpername)) {
                    require_once($helpername);
                } else {
                    die('could not find the helper file' . $helpername);
                }
            }
        }
        else{
            if (file_exists(HELPERS.$name)) {
                require_once(HELPERS.$name);
            } else {
                die('could not find the helper file' . $name. ' in helpers directory');
            }

        }
    }
    
    
}
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
            echo $querystring;
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
    protected function optionsToSQL($obj){
        //$obj[] = [option_name, db_field_name, value]
        $queries = [];
        foreach($obj as $option){
            //if value is not empty get query
            if(!empty($option[2])){
                $queries[] = $this->fieldToSQL($option);
            }
        }
        
    }
    protected function fieldToSQL($option){
        $option = $option[0];
        $field = $option[1];
        $value = $option[2];
        $query = "";
        if (is_array($value) and count($value) > 1) {
            $data = [];
            foreach ($value as $val) {
                $data[] = is_numeric($val) ? $val : "'{$val}'";
            }
            $join = implode(",", $data);
            $join = "(" . $join . ")";
            $query = " {$field} IN " . $join;
        } else {
            $val = is_array($value) ? $value[0] : $value;
            $val = is_numeric($val) ? $val : "'{$val}'";
            $query = " {$field} =  {$val}";
        }

        return $query;
        
    }
    protected function getSQL($obj)
    {

        $queries = [];
        $method = empty($obj->method) ? false : $this->queryBuilder($obj->method, "method");
        $mda = empty($obj->mda) ? false : $this->queryBuilder($obj->mda, "mda");
        $year = empty($obj->year) ? false : $this->queryBuilder($obj->year, "year");
        $published = empty($obj->year) ? false : $this->queryBuilder($obj->year, "published");
        $contractor = empty($obj->contractor) ? false : $this->queryBuilder($obj->contractor, "contractor");
        $text = empty($obj->text) ? false : $this->queryBuilder($obj->text, "text");
        $state = empty($obj->state) ? false : $this->queryBuilder($obj->state, "state");
        $cso_state = empty($obj->cso_state) ? false : $this->queryBuilder($obj->cso_state, "cso_state");
        $monitored = empty($obj->monitored) ? false : $this->queryBuilder($obj->monitored, "monitored");
        $sector = empty($obj->sector)? false: $this->queryBuilder($obj->sector, 'sector');
        $cso = empty($obj->cso)? false: $this->queryBuilder($obj->cso, 'cso');
        $lga = empty($obj->lga)? false: $this->queryBuilder($obj->lga, 'lga');
        if ($method) {
            $queries[] = $method;
        }
        if ($year) {
            $queries[] = $year;
        }
        if ($published) {
            $queries[] = $published;
        }
        if ($mda) {
            $queries[] = $mda;
        }
        if ($contractor) {
            $queries[] = $contractor;
        }
        if ($text) {
            $queries[] = $text;
        }
        if ($state) {
            $queries[] = $state;
        }
        if ($cso_state) {
            $queries[] = $cso_state;
        }
        if ($monitored) {
            $queries[] = $monitored;
        }
        if($lga) $queries [] = $lga;

        if($cso) $queries [] = $cso;
        return $queries;

    }
    private function queryBuilder($value, $type)
    {
        $query = "";
        switch ($type) {
            case "year":
                if (is_array($value) and count($value) > 1) {
                    $data = [];
                    foreach ($value as $val) {
                        $data[] = "'" . $val . "'";
                    }
                    $join = implode(",", $data);
                    $join = "(" . $join . ")";
                    $query = " p.year IN " . $join;
                } else {
                    $val = is_array($value) ? $value[0] : $value;
                    $query = " p.year = '" . $val . "' ";
                }
                break;
            case "lga":
                if (is_array($value) and count($value) > 1) {
                    $data = [];
                    foreach ($value as $val) {
                        $data[] = "'" . $val . "'";
                    }
                    $join = implode(",", $data);
                    $join = "(" . $join . ")";
                    $query = " p.lga IN " . $join;
                } else {
                    $val = is_array($value) ? $value[0] : $value;
                    $query = " p.lga = '" . $val . "' ";
                }
                break;

            case "state":
                if (is_array($value) and count($value) > 1) {
                    $data = [];
                    foreach ($value as $val) {
                        $data[] = "'" . $val . "'";
                    }
                    $join = implode(",", $data);
                    $join = "(" . $join . ")";
                    $query = " p.state IN " . $join . " ";
                } else {
                    $val = is_array($value) ? $value[0] : $value;
                    $query = "p.state = '" . $val . "' ";
                }
                break;
            case "cso_state":
                if (is_array($value) and count($value) > 1) {
                    $data = [];
                    foreach ($value as $val) {
                        $data[] = "'" . $val . "'";
                    }
                    $join = implode(",", $data);
                    $join = "(" . $join . ")";
                    $query = " c.state IN " . $join . " ";
                } else {
                    $val = is_array($value) ? $value[0] : $value;
                    $query = "c.state = '" . $val . "' ";
                }
                break;
            case "contractor":
                if (is_array($value) and count($value) > 1) {
                    $join = implode(",", $value);
                    $join = "(" . $join . ")";
                    $query = " ct.contractor_id IN " . $join . " ";
                } else {
                    $val = is_array($value) ? $value[0] : $value;
                    $query = " ct.contractor_id = " . $val . " ";
                }
                break;
            case "cso":
                if (is_array($value) and count($value) > 1) {
                    $join = implode(",", $value);
                    $join = "(" . $join . ")";
                    $query = " p.cso_id IN " . $join . " ";
                } else {
                    $val = is_array($value) ? $value[0] : $value;
                    $query = " p.cso_id = " . $val . " ";
                }
                break;
            case "mda":
                if (is_array($value) and count($value) > 1) {
                    $join = implode(",", $value);
                    $join = "(" . $join . ")";
                    $query = " p.mda_id IN " . $join . " ";
                } else {
                    $val = is_array($value) ? $value[0] : $value;
                    $query = " p.mda_id = " . $val . " ";
                }
                break;
            case "text":
                $query = " p.title LIKE '%" . $value . "%' ";
                break;
            case "method":
                if (is_array($value) and count($value) > 1) {
                    $data = [];
                    foreach ($value as $val) {
                        $data[] = "'" . $val . "'";
                    }
                    $join = implode(",", $data);
                    $join = "(" . $join . ")";
                    $query = " t.procurement_method IN " . $join . " ";
                } else {
                    $val = is_array($value) ? $value[0] : $value;
                    $query = " t.procurement_method = '" . $val . "' ";
                }
                break;
            case "published":
                $val = is_array($value) ? $value[0] : $value;
                $query = " p.published = '" . $val . "' ";
                break;
            case "sector":
                if (is_array($value) and count($value) > 1) {
                    $data = [];
                    foreach ($value as $val) {
                        $data[] = "'" . $val . "'";
                    }
                    $join = implode(",", $data);
                    $join = "(" . $join . ")";
                    $query = " m.sector IN " . $join . " ";
                } else {
                    $val = is_array($value) ? $value[0] : $value;
                    $query = " m.sector = '" . $val . "' ";
                }
                break;
            






        }
        return $query;
    }
    
    
}
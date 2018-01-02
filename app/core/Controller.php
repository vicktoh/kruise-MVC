<?php 
class Controller
{
    public $absPath = "http://localhost/budeshi-2.0/webroot/";
    public $fileroot = "C:/xampp/htdocs/budeshi-2.0/app/";
    public $notSet = [];

    public function check_login($array)
    {
        is_array($array) or die("invalid function parameter");
        session_start();
        $status = true;
        foreach ($array as $key) {
            if (isset($_SESSION[$key]) and !empty($_SESSION[$key])) {
                continue;
            } else {
                $status = false;
                break;
            }
        }
        return $status;
    }
    protected function filter_data($data)
    {
        $dataToReturn = trim($data);
        $dataToReturn = stripslashes($dataToReturn);
        $dataToReturn = htmlspecialchars($dataToReturn);
        return $dataToReturn;
    }
    public function view_variables($array)
    {
        foreach ($array as $key) {
            $array[$key] = $this->filter_data($array[$key]);
        }
        extract($array);
    }
    public function redirect($url)
    {
        if (!headers_sent()) {
            header("Location: " . ABS_PATH . $url);
        } else {
            die('Link Error: headers already sent');
        }
    }
    public function checkIfSet($array)
    {
        $status = true;
        foreach ($array as $value) {
            if (!isset($value)) {
                $status = false;
                $this->notSet[] = $value;
            }
        }
        return $status;
    }
    public function login($username, $password, $table = "users")
    {
        require_once("../app/core/Model.php");
        $query = "SELECT * FROM " . $table . " WHERE username = '" . $username . "' AND password = '" . $password . "'";
        $model = new Model();
        $result = $model->query($query);
        if (!$result) {
            die($this->error);
        }
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            session_start();
            $_SESSION["username"] = $row["username"];
            $_SESSION["id"] = $row["id"];
            $_SESSION["access_level"] = $row["access_id"];
            return true;
        } else {
            return false;
        }
    }
    public function request_method($type = "POST")
    {
        return $_SERVER["REQUEST_METHOD"];

    }
    /**
     * Validates and sanitizes an input post
     * @param string $name the name of the input variable
     * @param string $process_type can be either php validate types e.g FILTER_SANITIZE_STRING defaults to  "FILTER_SANITIZE_STRING";
     */
    public function input_post($name, $filter_type = FILTER_SANITIZE_STRING)
    {

        if (isset($_POST[$name]) and !empty($_POST[$name])) {
            
            return filter_input(INPUT_POST, $name);
        }
        else{
            return FALSE;
        }

    }
    public function input_get($name, $filter_type = FILTER_SANITIZE_STRING){
        if (isset($_POST[$name]) and !empty($_POST[$name])) {
            
            return filter_input(INPUT_POST, $name, $filter_type);
        }
        else{
            return FALSE;
        }

    }
}
<?php 
class Controller
{

    protected function load_view($view_name, $data)
    {
        $view_name = VIEWS . $view_name . ".html";
        if (file_exists($view_name)) {
            extract($this->view_variables($data));
            require_once($view_name);
        } else {
            die("cannot find the view file '" . $view_name . "'");

        }
    }
    protected function load_model($model_name)
    {
        $model_name = $model_name;
        $name = MODELS . $model_name . ".php";
        if (file_exists($name)) {
            require_once($name);
            return new $model_name;
        } else {
            die("Cannot find the specified model at " . $name);
        }
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
    }
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
    protected function view_variables($array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
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
    
    public function request_method($type = "POST")
    {
        return $_SERVER["REQUEST_METHOD"];

    }
    /**
     * Validates and sanitizes an input post
     * @param string $name the name of the input variable
     * @param string $filter_type can be either php validate types e.g FILTER_SANITIZE_STRING defaults to  "FILTER_SANITIZE_STRING";
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
    /**
     * Validates and sanitizes an get input
     * @param string $name the name of the input variable
     * @param string $filter_type can be either of php validate types e.g FILTER_SANITIZE_STRING: defaults to "FILTER_SANITIZE_STRING";
     */
    public function input_get($name, $filter_type = FILTER_SANITIZE_STRING){
        if (isset($_POST[$name]) and !empty($_POST[$name])) {
            
            return filter_input(INPUT_POST, $name, $filter_type);
        }
        else{
            return FALSE;
        }

    }
    protected function uploadfile($filename, $upload_path)
    {
        $tmp_name = $_FILES[$filename]["tmp_name"];
        
        if (move_uploaded_file($tmp_name, $upload_path)) {
            return $upload_path;
        } else {
            return false;
        }
    }
}
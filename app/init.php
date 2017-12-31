<?php
///MVC CONSTANTS
define("CONTROLLERS", "app/controllers");///path to your controllers
define("MODELS", "app/models");
define("VIEWS", "app/views");
/// You may define other constants of your own here
/// ***********************************DATABASE CONFIGURATIONS**********************************///
define("SQL_HOST", "localhost");
define("SQL_USER", "root");
define("SQL_PASS", "victor");
define("SQL_DB", "buddata");


define("DB_DRIVER", "mysqli"); /// other driver values include mysql, pdo, mongodb
define("ABS_PATH", "http://localhost/budeshi-2.0/webroot/");
define("FILE_ROOT", "C:/xampp/htdocs/budeshi-2.0/app/"); /// file root for for file upload functionalities
define("WEB_ROOT", "C:/xampp/htdocs/budeshi-2.0/webroot/"); /// absolute  system path to your on the server or local machine to your root folder mostimes the same folder as index.php file
define("ASSET_PATH", "");
define("OC_PREFIX", "azam7x");
define("RELEASE_PATH", ABS_PATH . "Raw/");


///**************************************************************Core Classes you want readily available for you add them to the core array and make sure the files are available at * app/core/mycoreclasses.php****************************************//////
$core = array();
$core[] = "App";
$core[] = "Controller";
$core[] = "Model"; 
///add some more the same way $core[] = "Name_of_Core_class";

foreach ($core as $class) {
    if (file_exists("core/" . $class . "php")) {
        require_once("core/" . $class . "php");
    } else {
        echo "The Core class " . $class . " could not be found at app/core/" . $class . "php";
        die();

    }
}
?>